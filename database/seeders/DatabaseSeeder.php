<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\KnowledgeArticle;
use App\Models\Payment;
use App\Models\Post;
use App\Models\Product;
use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['admin', 'manager', 'staff'] as $role) {
            Role::findOrCreate($role);
        }

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '(512) 555-0100',
            ]
        );
        $admin->syncRoles(['admin']);

        User::query()->updateOrCreate(
            ['email' => 'manager@driftlineirrigation.com'],
            [
                'name' => 'Sofia Reyes',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'phone' => '(512) 555-0101',
            ]
        )->syncRoles(['manager']);

        User::query()->updateOrCreate(
            ['email' => 'crew@driftlineirrigation.com'],
            [
                'name' => 'Nate Caldwell',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'phone' => '(512) 555-0102',
            ]
        )->syncRoles(['staff']);

        $settings = [
            'company_name' => 'Driftline Irrigation Co.',
            'company_phone' => '(512) 555-0194',
            'company_email' => 'hello@driftlineirrigation.com',
            'whatsapp' => '15125550194',
            'service_area' => 'Austin, Round Rock, Georgetown, Cedar Park & Central Texas',
            'tax_rate' => '8.25',
        ];
        foreach ($settings as $key => $value) {
            Setting::setValue($key, $value);
        }

        $products = [
            ['name' => 'Rotor Spray Head Pack (6)', 'sku' => 'IRR-ROT-6', 'category' => 'heads', 'unit_cost' => 48, 'unit_price' => 89, 'stock_qty' => 54],
            ['name' => 'Matched Precip Nozzle Kit', 'sku' => 'IRR-NZL-MP', 'category' => 'nozzles', 'unit_cost' => 32, 'unit_price' => 62, 'stock_qty' => 70],
            ['name' => '1" Zone Valve Assembly', 'sku' => 'IRR-VLV-1', 'category' => 'valves', 'unit_cost' => 28, 'unit_price' => 55, 'stock_qty' => 40],
            ['name' => 'Wi‑Fi Smart Controller (8-zone)', 'sku' => 'IRR-CTL-8', 'category' => 'controllers', 'unit_cost' => 145, 'unit_price' => 265, 'stock_qty' => 18],
            ['name' => 'Drip Emitter Coil (100 ft)', 'sku' => 'IRR-DRP-100', 'category' => 'drip', 'unit_cost' => 22, 'unit_price' => 44, 'stock_qty' => 36],
            ['name' => 'Pressure Regulator 25 PSI', 'sku' => 'IRR-PR-25', 'category' => 'drip', 'unit_cost' => 12, 'unit_price' => 28, 'stock_qty' => 48],
            ['name' => 'Rain/Freeze Sensor', 'sku' => 'IRR-SNS-RF', 'category' => 'sensors', 'unit_cost' => 35, 'unit_price' => 68, 'stock_qty' => 25],
            ['name' => 'PVC Lateral Fitting Assortment', 'sku' => 'IRR-FIT-AS', 'category' => 'fittings', 'unit_cost' => 40, 'unit_price' => 75, 'stock_qty' => 22],
        ];
        foreach ($products as $product) {
            Product::query()->updateOrCreate(['sku' => $product['sku']], $product + ['unit' => 'kit', 'is_active' => true]);
        }

        $customers = collect([
            ['name' => 'Hannah Brooks', 'email' => 'hannah@example.com', 'phone' => '(512) 555-3301', 'company' => null, 'city' => 'Austin', 'state' => 'TX', 'zip' => '78731', 'address' => '4201 Scenic Ridge Dr'],
            ['name' => 'Omar Hassan', 'email' => 'omar@oakparkhoa.com', 'phone' => '(512) 555-3310', 'company' => 'Oak Park HOA', 'city' => 'Round Rock', 'state' => 'TX', 'zip' => '78664', 'address' => '1100 Oak Park Blvd'],
            ['name' => 'Lila Chen', 'email' => 'facilities@ridgewayoffice.com', 'phone' => '(512) 555-3399', 'company' => 'Ridgeway Office Park', 'city' => 'Austin', 'state' => 'TX', 'zip' => '78759', 'address' => '8800 Research Blvd'],
            ['name' => 'Tom Vargas', 'email' => 'tom.vargas@example.com', 'phone' => '(737) 555-3188', 'company' => null, 'city' => 'Cedar Park', 'state' => 'TX', 'zip' => '78613', 'address' => '19 Brushy Creek Ln'],
            ['name' => 'Priya Shah', 'email' => 'priya@georgetownretail.com', 'phone' => '(512) 555-3177', 'company' => 'Georgetown Retail Plaza', 'city' => 'Georgetown', 'state' => 'TX', 'zip' => '78626', 'address' => '450 Williams Dr'],
        ])->map(fn ($c) => Customer::query()->updateOrCreate(['email' => $c['email']], $c));

        $projects = [
            ['customer' => 0, 'title' => '8-zone residential sprinkler rebuild', 'service_type' => 'residential', 'status' => 'scheduled', 'sq_ft' => 8500, 'quoted_amount' => 6200],
            ['customer' => 1, 'title' => 'HOA frontage smart controller upgrade', 'service_type' => 'smart', 'status' => 'in_progress', 'sq_ft' => 22000, 'quoted_amount' => 14800],
            ['customer' => 2, 'title' => 'Office park drip conversion beds', 'service_type' => 'drip', 'status' => 'quoted', 'sq_ft' => 14000, 'quoted_amount' => 18600],
            ['customer' => 3, 'title' => 'Leak repair + nozzle match', 'service_type' => 'repair', 'status' => 'completed', 'sq_ft' => 4200, 'quoted_amount' => 980, 'final_amount' => 1120],
            ['customer' => 4, 'title' => 'Retail plaza seasonal tune-up', 'service_type' => 'commercial', 'status' => 'invoiced', 'sq_ft' => 18000, 'quoted_amount' => 2400, 'final_amount' => 2350],
        ];

        $projectModels = [];
        foreach ($projects as $i => $p) {
            $customer = $customers[$p['customer']];
            $projectModels[] = Project::query()->updateOrCreate(
                ['job_number' => 'JOB-26'.str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT)],
                [
                    'customer_id' => $customer->id,
                    'title' => $p['title'],
                    'service_type' => $p['service_type'],
                    'status' => $p['status'],
                    'sq_ft' => $p['sq_ft'],
                    'quoted_amount' => $p['quoted_amount'],
                    'final_amount' => $p['final_amount'] ?? null,
                    'start_date' => now()->subDays(10 - $i)->toDateString(),
                    'site_address' => trim(($customer->address ?? '').', '.$customer->city.', '.$customer->state),
                    'notes' => 'Seeded demo job for Driftline ERP.',
                ]
            );
        }

        foreach ([0, 1, 3, 4] as $idx) {
            $project = $projectModels[$idx];
            $invoice = Invoice::query()->updateOrCreate(
                ['invoice_number' => 'INV-26'.str_pad((string) ($idx + 1), 3, '0', STR_PAD_LEFT)],
                [
                    'customer_id' => $project->customer_id,
                    'project_id' => $project->id,
                    'issue_date' => now()->subDays(8 - $idx)->toDateString(),
                    'due_date' => now()->addDays(14)->toDateString(),
                    'subtotal' => $project->final_amount ?? $project->quoted_amount,
                    'tax' => round(($project->final_amount ?? $project->quoted_amount) * 0.0825, 2),
                    'total' => round(($project->final_amount ?? $project->quoted_amount) * 1.0825, 2),
                    'amount_paid' => in_array($project->status, ['completed', 'invoiced'], true) ? round(($project->final_amount ?? $project->quoted_amount) * 1.0825, 2) : 0,
                    'status' => in_array($project->status, ['completed', 'invoiced'], true) ? 'paid' : 'sent',
                ]
            );

            if ($invoice->status === 'paid') {
                Payment::query()->updateOrCreate(
                    ['reference' => 'PMT-'.$invoice->invoice_number],
                    [
                        'invoice_id' => $invoice->id,
                        'amount' => $invoice->total,
                        'paid_at' => now()->subDays(3)->toDateString(),
                        'method' => 'card',
                        'notes' => 'Demo payment',
                    ]
                );
            }
        }

        Appointment::query()->updateOrCreate(
            ['title' => 'Water audit — Brooks'],
            [
                'customer_id' => $customers[0]->id,
                'project_id' => $projectModels[0]->id,
                'type' => 'audit',
                'starts_at' => now()->addDays(2)->setTime(9, 0),
                'ends_at' => now()->addDays(2)->setTime(10, 0),
                'status' => 'scheduled',
                'location' => $customers[0]->address,
            ]
        );
        Appointment::query()->updateOrCreate(
            ['title' => 'HOA controller install day'],
            [
                'customer_id' => $customers[1]->id,
                'project_id' => $projectModels[1]->id,
                'type' => 'install',
                'starts_at' => now()->addDays(9)->setTime(7, 0),
                'ends_at' => now()->addDays(9)->setTime(17, 0),
                'status' => 'confirmed',
                'location' => $customers[1]->address,
            ]
        );

        $posts = [
            [
                'title' => 'Why Austin Lawns Brown Even With “Enough” Water',
                'excerpt' => 'Uneven coverage and mismatched nozzles waste water and stress turf — here is how Driftline fixes both.',
                'cover' => 'https://images.unsplash.com/photo-1592982537447-7440770cbfc9?auto=format&fit=crop&w=1400&q=80',
                'body' => "Brown patches are rarely a mystery: mixed nozzle types, low pressure at the far head, and schedules that ignore ET. In Central Texas summers, mismatched precipitation turns wet arcs into dry islands overnight.\n\nDriftline pressure-tests every zone, swaps to matched-precipitation nozzles, and programs controllers around real plant needs. If your lawn looks thirsty while the water meter spins, stop guessing and book a water audit.",
            ],
            [
                'title' => 'Spray vs Drip: Choosing the Right System for Each Bed',
                'excerpt' => 'Turf loves spray. Mulched beds love drip. Mixing them poorly wastes gallons.',
                'cover' => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?auto=format&fit=crop&w=1400&q=80',
                'body' => "Spray heads excel on open turf with even spacing. Drip emitters shine in foundation beds, shrubs, and xeriscape where evaporation and overspray kill efficiency.\n\nFor commercial campuses we often keep spray on lawn panels and convert decorative beds to filtered drip with pressure regulation. Match the delivery method to the plant — not the old timer’s zone labels.",
            ],
            [
                'title' => 'Smart Controllers That Actually Cut Water Bills',
                'excerpt' => 'Wi‑Fi alone is not enough — weather intelligence and zone reporting matter.',
                'cover' => 'https://images.unsplash.com/photo-1558002038-1055907df827?auto=format&fit=crop&w=1400&q=80',
                'body' => "A smart controller that still runs 20 minutes every zone every dawn is just a pretty clock. Driftline configures weather-aware schedules, rain/freeze sensors, and zone-level runtimes based on plant type and sun exposure.\n\nHomeowners and property managers get remote visibility without babysitting the timer box. Ask us for a controller upgrade when your water bill climbs faster than the grass.",
            ],
        ];
        foreach ($posts as $post) {
            Post::query()->updateOrCreate(
                ['slug' => Str::slug($post['title'])],
                [
                    'title' => $post['title'],
                    'excerpt' => $post['excerpt'],
                    'body' => $post['body'],
                    'cover_image' => $post['cover'],
                    'is_published' => true,
                    'published_at' => now()->subDays(random_int(2, 20)),
                ]
            );
        }

        $articles = [
            ['category' => 'issues', 'title' => 'Zone will not turn on', 'body' => "1. Confirm controller has power and the program is not paused.\n2. Check rain/freeze sensor status.\n3. Listen for the valve solenoid click; swap solenoids if needed.\n4. Inspect wiring continuity and valve diaphragm.\n5. Verify main shutoff and backflow are open."],
            ['category' => 'issues', 'title' => 'Misty spray and fogging heads', 'body' => "High pressure atomizes water into mist that drifts and evaporates. Install a pressure-regulating head or PRS valve, reduce zone pressure, and recheck nozzle charts. Fogging in Austin heat is wasted gallons."],
            ['category' => 'guides', 'title' => 'Residential install day timeline', 'body' => "Day 0: Mark utilities, confirm meter/backflow access.\nMorning: Trench laterals, set heads and valves.\nMidday: Glue fittings, flush lines, set nozzles.\nAfternoon: Wire controller, program zones, walk coverage.\nHandoff: Review schedules, sensor tips, and warranty packet."],
            ['category' => 'guides', 'title' => 'How to prepare for a water audit', 'body' => "Know where the controller and backflow are. Note wet spots and dry patches. Have recent water bills handy. Clear gate access. Driftline will pressure-test zones, map coverage, and recommend repairs or redesign."],
            ['category' => 'admin', 'title' => 'Admin ERP quick start', 'body' => "Login at /admin with admin@example.com / password.\n1. Add customers in CRM.\n2. Create a Job/Project with sq ft and quote.\n3. Schedule appointments for audit/install.\n4. Convert completed jobs to invoices and record payments.\n5. Use AI Studio for blog drafts and troubleshooting suggestions.\n6. Update company Settings for phone, WhatsApp, and tax rate."],
            ['category' => 'admin', 'title' => 'Roles: Admin, Manager, Staff', 'body' => "Admin: full ERP + settings + users.\nManager: jobs, inventory, invoices, scheduling.\nStaff: field-facing records and appointment updates.\nAssign roles on the Users resource."],
            ['category' => 'troubleshoot', 'title' => 'Job running over fitting estimate', 'body' => "Check actual trench length vs quote. Confirm extra fittings for rock or redesign. Log additional kits on the job notes, create a change-order invoice line, and update inventory stock quantities."],
            ['category' => 'troubleshoot', 'title' => 'Chatbot giving demo answers only', 'body' => "Demo mode is expected without API keys. Add OPENAI_API_KEY or GEMINI_API_KEY to .env, then clear config cache. The public chatbot and AI Studio will prefer live models when keys are present."],
        ];
        foreach ($articles as $article) {
            KnowledgeArticle::query()->updateOrCreate(
                ['slug' => Str::slug($article['title'])],
                [
                    'title' => $article['title'],
                    'category' => $article['category'],
                    'body' => $article['body'],
                    'is_published' => true,
                ]
            );
        }
    }
}
