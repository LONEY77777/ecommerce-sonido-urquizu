<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    /**
     * Seed the catalogue with a representative range of stationery.
     */
    public function run(): void
    {
        foreach ($this->products() as $product) {
            $product['image'] = $this->publishImage($product['image']);

            Product::updateOrCreate(['name' => $product['name']], $product);
        }
    }

    /**
     * Copy bundled artwork onto the public disk and return the stored path.
     *
     * The artwork is committed under `resources/images/products`, because
     * `storage/app/public` is not tracked in git — seeding republishes it so a
     * fresh clone still gets a catalogue with pictures.
     */
    private function publishImage(string $filename): ?string
    {
        $source = resource_path('images/products/'.$filename);

        if (! is_file($source)) {
            return null;
        }

        $path = 'products/'.$filename;

        Storage::disk('public')->put($path, file_get_contents($source));

        return $path;
    }

    /**
     * The sample catalogue.
     *
     * @return list<array{name: string, category: string, price: float, description: string, stock: int, image: string}>
     */
    private function products(): array
    {
        return [
            [
                'name' => 'Pilot G2 Gel Pen (Pack of 12)',
                'category' => 'Writing',
                'price' => 1650.00,
                'description' => 'The retractable gel pen that made smooth writing the standard. A 0.7mm tip lays down rich, quick-drying black ink that will not smear under your hand, and the contoured rubber grip keeps long note-taking sessions comfortable. Refillable, so one barrel outlasts a dozen disposables.',
                'stock' => 140,
                'image' => 'pilot-g2-gel-pen.svg',
            ],
            [
                'name' => 'Faber-Castell Graphite Pencil Set (12 Grades)',
                'category' => 'Writing',
                'price' => 2050.00,
                'description' => 'Twelve bonded-lead pencils running from a feather-light 6H to a velvety 6B, presented in a slim tin. The break-resistant lead core survives sharpening and drops alike, making this the set art students are told to buy in their first week.',
                'stock' => 85,
                'image' => 'faber-castell-pencils.svg',
            ],
            [
                'name' => 'Sakura Pigma Micron Fineliner Set (6 Sizes)',
                'category' => 'Writing',
                'price' => 2300.00,
                'description' => 'Archival pigment ink in six nib widths from 005 to 08. The ink is waterproof, fade-resistant and bleeds through nothing, which is why it has become the default pen for technical illustration, bullet journals and comic inking.',
                'stock' => 60,
                'image' => 'sakura-micron-fineliners.svg',
            ],
            [
                'name' => 'Moleskine Classic Notebook — Ruled, Large',
                'category' => 'Paper',
                'price' => 2750.00,
                'description' => 'The pocketable hardcover notebook with 240 acid-free ruled pages, a ribbon marker, an elastic closure and the expandable inner pocket that swallows receipts and tickets. Lies perfectly flat at any page.',
                'stock' => 95,
                'image' => 'moleskine-notebook.svg',
            ],
            [
                'name' => 'Rhodia Dot Pad No. 16 (A5)',
                'category' => 'Paper',
                'price' => 1080.00,
                'description' => 'Eighty sheets of famously smooth 80g Clairefontaine paper with a 5mm dot grid, micro-perforated for a clean tear. Fountain pen ink sits on top of this paper instead of feathering into it.',
                'stock' => 120,
                'image' => 'rhodia-dot-pad.svg',
            ],
            [
                'name' => 'Premium A4 Copy Paper — 500 Sheets',
                'category' => 'Paper',
                'price' => 620.00,
                'description' => 'A full ream of bright white 80gsm multipurpose paper cut for laser printers, inkjets and copiers. Low-dust construction keeps jams and streaks out of high-volume office printing.',
                'stock' => 300,
                'image' => 'copy-paper-ream.svg',
            ],
            [
                'name' => 'Post-it Super Sticky Notes (Pack of 12)',
                'category' => 'Paper',
                'price' => 1790.00,
                'description' => 'Twelve 76mm pads in assorted brights, with adhesive engineered to hold on monitors, whiteboards and painted walls where standard notes peel off by lunchtime.',
                'stock' => 200,
                'image' => 'post-it-notes.svg',
            ],
            [
                'name' => 'Winsor & Newton Cotman Watercolour Set (12 Half Pans)',
                'category' => 'Art Supplies',
                'price' => 3520.00,
                'description' => 'Twelve half pans of finely milled watercolour in a pocket tin that doubles as a mixing palette, complete with a travel brush. Strong tinting strength and reliable lightfastness at a student-friendly price.',
                'stock' => 45,
                'image' => 'cotman-watercolour-set.svg',
            ],
            [
                'name' => 'Prismacolor Premier Coloured Pencils (48 Count)',
                'category' => 'Art Supplies',
                'price' => 6040.00,
                'description' => 'Soft, thick wax cores that layer, blend and burnish like paint. Forty-eight richly pigmented colours ideal for portraits, botanical work and anything demanding smooth gradients.',
                'stock' => 38,
                'image' => 'prismacolor-pencils.svg',
            ],
            [
                'name' => 'Canson XL Mixed Media Sketchbook (A4)',
                'category' => 'Art Supplies',
                'price' => 1500.00,
                'description' => 'Sixty sheets of heavyweight 160gsm paper with enough tooth for graphite and charcoal and enough body for light washes. Side-wire binding folds flat and pages tear out cleanly.',
                'stock' => 70,
                'image' => 'canson-sketchbook.svg',
            ],
            [
                'name' => 'Bamboo Desk Organiser',
                'category' => 'Desk Accessories',
                'price' => 3300.00,
                'description' => 'Sustainably sourced bamboo with six compartments sized for pens, sticky notes, business cards and a phone. Felt pads underneath keep it from marking the desk.',
                'stock' => 42,
                'image' => 'bamboo-desk-organiser.svg',
            ],
            [
                'name' => 'Heavy-Duty Stapler with 1000 Staples',
                'category' => 'Desk Accessories',
                'price' => 1180.00,
                'description' => 'An all-metal stapler that drives cleanly through 40 sheets without jamming, with a spring-loaded magazine, a non-slip base and a starter box of 1000 staples.',
                'stock' => 65,
                'image' => 'heavy-duty-stapler.svg',
            ],
            [
                'name' => 'Precision Scissors — Titanium Bonded 8"',
                'category' => 'Desk Accessories',
                'price' => 890.00,
                'description' => 'Titanium-bonded blades stay sharp roughly three times longer than stainless steel and shrug off tape adhesive. Softly cushioned handles reduce hand fatigue on long cutting jobs.',
                'stock' => 88,
                'image' => 'precision-scissors.svg',
            ],
            [
                'name' => 'Leitz Lever Arch File — Pack of 5',
                'category' => 'Filing & Storage',
                'price' => 2450.00,
                'description' => 'Five 80mm A4 lever arch files in a hardwearing laminated finish, with a thumb hole for pulling them off high shelves and a replaceable spine label.',
                'stock' => 55,
                'image' => 'lever-arch-files.svg',
            ],
            [
                'name' => 'Expanding File Folder — 13 Pockets',
                'category' => 'Filing & Storage',
                'price' => 780.00,
                'description' => 'A water-resistant accordion folder with thirteen labelled pockets and an elastic closure, sized for A4 documents. Keeps a year of receipts or a term of handouts in one place.',
                'stock' => 78,
                'image' => 'expanding-file-folder.svg',
            ],
            [
                'name' => 'Document Storage Box — Pack of 3',
                'category' => 'Filing & Storage',
                'price' => 1350.00,
                'description' => 'Three stackable corrugated archive boxes with reinforced handles and lift-off lids, each holding roughly 2500 A4 sheets. Flat-packed until you need them.',
                'stock' => 40,
                'image' => 'document-storage-boxes.svg',
            ],
            [
                'name' => 'Casio FX-991EX Scientific Calculator',
                'category' => 'School Supplies',
                'price' => 2890.00,
                'description' => 'A 552-function classroom calculator with a high-resolution display that renders fractions, matrices and integrals as they appear in a textbook. Solar powered with battery backup and approved for most examinations.',
                'stock' => 50,
                'image' => 'casio-calculator.svg',
            ],
            [
                'name' => 'Geometry Instrument Set (9 Pieces)',
                'category' => 'School Supplies',
                'price' => 480.00,
                'description' => 'A metal tin holding a self-centring compass, divider, set squares, protractor, 15cm ruler, pencil and eraser — every instrument on the standard school stationery list in one case.',
                'stock' => 110,
                'image' => 'geometry-set.svg',
            ],
            [
                'name' => 'Highlighter Pens — Pastel Set of 6',
                'category' => 'School Supplies',
                'price' => 640.00,
                'description' => 'Six chisel-tip highlighters in muted pastel shades that stay readable in photocopies and under exam-hall lighting. Quick-drying ink that does not bleed through textbook paper.',
                'stock' => 165,
                'image' => 'highlighter-pens.svg',
            ],
            [
                'name' => 'Refillable Hardcover Binder Notebook (B5)',
                'category' => 'Paper',
                'price' => 2900.00,
                'description' => 'A six-ring hardcover binder supplied with 120 sheets of 100gsm dot-grid paper, a pen loop and two index dividers. Reorganise, remove or add pages without starting a new notebook.',
                'stock' => 0,
                'image' => 'binder-notebook.svg',
            ],
        ];
    }
}
