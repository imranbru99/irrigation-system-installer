<?php

namespace App\Services;

use App\Models\ChatLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AiService
{
    /** @var array<int, array{q: string, a: string}> */
    protected array $faq = [
        [
            'q' => 'how long install timeline schedule',
            'a' => 'Most residential sprinkler installs take 1–3 days depending on lot size and trenching conditions. Commercial campuses are phased by zone groups. Driftline typically books water audits within 2–3 business days across Austin and Central Texas.',
        ],
        [
            'q' => 'cost price estimate quote sprinkler system',
            'a' => 'Residential sprinkler systems in Central Texas often range from about $2,500–$8,000+ depending on zones, controller type, and site access. Drip add-ons and smart controllers vary. Request a free water audit for an exact written quote.',
        ],
        [
            'q' => 'drip xeriscape emitter beds trees',
            'a' => 'We install pressure-regulated drip for beds, shrubs, and trees — ideal for xeriscape and mulched plantings where spray waste is high. Filtration and proper emitter spacing keep systems reliable in Austin hard water.',
        ],
        [
            'q' => 'smart controller wifi weather schedule',
            'a' => 'Driftline upgrades legacy timers to Wi‑Fi smart controllers with weather-aware schedules, remote adjustments, and zone reporting. We migrate programs and train homeowners or facilities teams.',
        ],
        [
            'q' => 'leak repair wet spot broken head valve',
            'a' => 'Wet spots, hissing valves, and sudden bill spikes usually mean a lateral leak or stuck valve. We pressure-test zones, locate breaks, and repair fittings, valves, and heads — then recommend redesign if the layout is the root cause.',
        ],
        [
            'q' => 'commercial hoa campus property manager',
            'a' => 'For HOAs, offices, and retail campuses we document as-built zone maps, specify commercial valves and flow sensors, and phase work around tenants. Property managers get clear service packets and seasonal adjustment plans.',
        ],
        [
            'q' => 'schedule appointment audit contact',
            'a' => 'Call or text (512) 555-0194, use the contact form, or WhatsApp us. Water audits for Austin, Round Rock, Georgetown, Cedar Park, and nearby Central Texas cities are usually available within 2–3 business days.',
        ],
        [
            'q' => 'winterize freeze rain sensor seasonal',
            'a' => 'Central Texas rarely needs full winterization like northern states, but freeze sensors and schedule cutbacks matter. Our seasonal tune-ups align heads, clean filters, and adjust ET-based runtimes for spring and summer.',
        ],
    ];

    public function chat(string $message, ?string $sessionId = null): array
    {
        $reply = null;
        $source = 'demo';

        if ($key = config('services.openai.key')) {
            try {
                $reply = $this->callOpenAi($message, $key);
                $source = 'openai';
            } catch (\Throwable) {
                $reply = null;
            }
        }

        if (! $reply && ($gemini = config('services.gemini.key'))) {
            try {
                $reply = $this->callGemini($message, $gemini);
                $source = 'gemini';
            } catch (\Throwable) {
                $reply = null;
            }
        }

        if (! $reply) {
            $reply = $this->demoReply($message);
            $source = 'demo';
        }

        ChatLog::query()->create([
            'session_id' => $sessionId,
            'message' => $message,
            'reply' => $reply,
            'source' => $source,
        ]);

        return ['reply' => $reply, 'source' => $source];
    }

    public function generate(string $type, string $prompt): string
    {
        $message = match ($type) {
            'blog' => "Write a concise SEO blog post outline and intro for an irrigation system installer about: {$prompt}",
            'description' => "Write a compelling product/service description for irrigation systems: {$prompt}",
            'automation' => "Suggest 5 practical AI automations for an irrigation SME ERP related to: {$prompt}",
            'troubleshoot' => "Troubleshoot this irrigation operations issue with clear steps: {$prompt}",
            default => $prompt,
        };

        $result = $this->chat($message);

        return $result['reply'];
    }

    protected function demoReply(string $message): string
    {
        $hay = Str::lower($message);
        $best = null;
        $bestScore = 0;

        foreach ($this->faq as $item) {
            $score = 0;
            foreach (explode(' ', $item['q']) as $word) {
                if ($word !== '' && str_contains($hay, $word)) {
                    $score++;
                }
            }
            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $item['a'];
            }
        }

        if ($best && $bestScore > 0) {
            return $best;
        }

        return 'Thanks for reaching Driftline Irrigation Co. We design and install residential, commercial, drip, and smart irrigation systems across Austin and Central Texas. Ask about pricing, leaks, controllers, or scheduling—or call (512) 555-0194 for a free water audit.';
    }

    protected function callOpenAi(string $message, string $key): string
    {
        $response = Http::withToken($key)
            ->timeout(30)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => config('services.openai.model', 'gpt-4o-mini'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are the Driftline Irrigation Co. assistant for an Austin, TX irrigation system installer. Be concise, practical, and focused on residential/commercial sprinklers, drip, smart controllers, and leak repair. Never invent illegal claims.',
                    ],
                    ['role' => 'user', 'content' => $message],
                ],
                'temperature' => 0.4,
            ]);

        $response->throw();

        return (string) data_get($response->json(), 'choices.0.message.content', 'Unable to generate a response.');
    }

    protected function callGemini(string $message, string $key): string
    {
        $model = config('services.gemini.model', 'gemini-1.5-flash');
        $response = Http::timeout(30)
            ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$key}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => "You are Driftline Irrigation Co. assistant (Austin irrigation systems). Answer briefly:\n{$message}"],
                        ],
                    ],
                ],
            ]);

        $response->throw();

        return (string) data_get($response->json(), 'candidates.0.content.parts.0.text', 'Unable to generate a response.');
    }
}
