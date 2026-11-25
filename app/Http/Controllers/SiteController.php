<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\KnowledgeArticle;
use App\Models\Post;
use App\Services\AiService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        $posts = Post::query()->where('is_published', true)->latest('published_at')->take(3)->get();

        return view('pages.home', compact('posts'));
    }

    public function services(): View
    {
        return view('pages.services');
    }

    public function serviceShow(string $slug): View
    {
        $services = $this->serviceCatalog();
        abort_unless(isset($services[$slug]), 404);

        return view('services.show', [
            'slug' => $slug,
            'service' => $services[$slug],
            'services' => $services,
        ]);
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function contactStore(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['nullable', 'string', 'max:180'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactMessage::query()->create($data);

        return back()->with('success', 'Thanks! Driftline will follow up within one business day.');
    }

    public function blog(): View
    {
        $posts = Post::query()->where('is_published', true)->latest('published_at')->paginate(9);

        return view('blog.index', compact('posts'));
    }

    public function blogShow(string $slug): View
    {
        $post = Post::query()->where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('blog.show', compact('post'));
    }

    public function knowledge(): View
    {
        $articles = KnowledgeArticle::query()->where('is_published', true)->orderBy('category')->orderBy('title')->get()->groupBy('category');

        return view('kb.index', compact('articles'));
    }

    public function knowledgeShow(string $slug): View
    {
        $article = KnowledgeArticle::query()->where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('kb.show', compact('article'));
    }

    public function chat(Request $request, AiService $ai)
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'session_id' => ['nullable', 'string', 'max:80'],
        ]);

        $result = $ai->chat($data['message'], $data['session_id'] ?? null);

        return response()->json($result);
    }

    /** @return array<string, array<string, string>> */
    protected function serviceCatalog(): array
    {
        return [
            'residential-sprinklers' => [
                'title' => 'Residential Sprinkler Systems',
                'summary' => 'Matched-precipitation spray and rotor zones for lawns that green evenly in Austin heat.',
                'body' => 'Driftline designs pressure-balanced residential systems with proper head spacing, zone sequencing, and rain/freeze sensor options. We trench cleanly, backfill carefully, and program controllers for Central Texas ET rates so you are not overwatering in March or underwatering in August.',
                'image' => 'https://images.unsplash.com/photo-1592982537447-7440770cbfc9?auto=format&fit=crop&w=1600&q=80',
            ],
            'drip-xeriscape' => [
                'title' => 'Drip Irrigation & Xeriscape',
                'summary' => 'Low-flow emitters for beds, trees, and native plantings that thrive on less water.',
                'body' => 'From foundation beds to full xeriscape conversions, we run drip laterals with pressure regulators and filtration so emitters stay clean. Ideal for mulched beds, shrubs, and trees where spray waste is common.',
                'image' => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?auto=format&fit=crop&w=1600&q=80',
            ],
            'commercial-irrigation' => [
                'title' => 'Commercial Campus Irrigation',
                'summary' => 'Scalable controllers and zone maps for HOAs, offices, and retail campuses.',
                'body' => 'We phase installs around tenant schedules, document as-built zone maps, and specify commercial-grade valves and flow sensors. Property managers get clear service packets and seasonal adjustment plans.',
                'image' => 'https://images.unsplash.com/photo-1464226184884-fa280b87c309?auto=format&fit=crop&w=1600&q=80',
            ],
            'smart-controllers' => [
                'title' => 'Smart Controllers & Wi‑Fi Upgrades',
                'summary' => 'Weather-aware schedules that cut water waste without stressing landscapes.',
                'body' => 'Upgrade legacy timers to Wi‑Fi controllers with weather intelligence, zone-level reporting, and remote adjustments. We wire sensors, migrate programs, and train homeowners or facilities teams.',
                'image' => 'https://images.unsplash.com/photo-1558002038-1055907df827?auto=format&fit=crop&w=1600&q=80',
            ],
            'leak-repair' => [
                'title' => 'Leak Detection & Repair',
                'summary' => 'Find wet spots, hissing valves, and mainline breaks before the water bill spikes.',
                'body' => 'Driftline pressure-tests zones, listens for lateral leaks, and repairs valves, fittings, and broken heads. We document findings so you know whether a patch or a redesign is the smarter spend.',
                'image' => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?auto=format&fit=crop&w=1600&q=80',
            ],
            'seasonal-service' => [
                'title' => 'Seasonal Start-Up & Tune-Ups',
                'summary' => 'Spring activations, mid-summer audits, and schedule adjustments for Texas weather swings.',
                'body' => 'Seasonal packages include controller programming, head alignment, filter cleaning, and nozzle matching. Keep systems efficient year-round without calling every time a zone looks dry.',
                'image' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1600&q=80',
            ],
        ];
    }
}
