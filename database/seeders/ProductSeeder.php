<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Business Essentials
            [
                'name' => 'Name/Business Cards',
                'slug' => 'name-business-cards',
                'description' => 'Professional business cards with high-quality printing. Perfect for networking and making lasting first impressions. Available in standard and premium finishes.',
                'category' => 'Business Essentials',
                'subcategory' => 'Identity Printing',
                'base_price' => 99.99,
                'pricing_options' => json_encode([
                    ['quantity' => 500, 'price' => 99.99],
                    ['quantity' => 1000, 'price' => 149.99],
                ]),
                'specifications' => json_encode([
                    'size' => '85mm x 55mm',
                    'paper' => '350gsm Premium',
                    'finish' => 'Gloss, Matte, UV, Foil',
                    'turnaround' => '2-3 business days'
                ]),
                'design_templates' => json_encode(['minimal', 'corporate', 'creative', 'elegant', 'luxury']),
                'sort_order' => 1
            ],
            [
                'name' => 'Flyers',
                'slug' => 'flyers',
                'description' => 'High-impact flyers perfect for promotions, events, and marketing campaigns. Full-color printing on quality paper with various size options.',
                'category' => 'Business Essentials',
                'subcategory' => 'Marketing Materials',
                'base_price' => 39.99,
                'pricing_options' => json_encode([
                    ['quantity' => 100, 'price' => 39.99],
                    ['quantity' => 250, 'price' => 69.99],
                    ['quantity' => 500, 'price' => 119.99],
                    ['quantity' => 1000, 'price' => 199.99],
                ]),
                'specifications' => json_encode([
                    'size' => 'A4 (210mm x 297mm), A5, DL',
                    'paper' => '170gsm Gloss, Matte',
                    'finish' => 'Single-sided, Double-sided',
                    'turnaround' => '2-3 business days'
                ]),
                'design_templates' => json_encode(['event', 'promotion', 'business', 'creative', 'retail']),
                'sort_order' => 2
            ],
            [
                'name' => 'Brochures',
                'slug' => 'brochures',
                'description' => 'Detailed product and service information in professional brochure format. Perfect for comprehensive business presentations and marketing materials.',
                'category' => 'Business Essentials',
                'subcategory' => 'Marketing Materials',
                'base_price' => 59.99,
                'pricing_options' => json_encode([
                    ['quantity' => 100, 'price' => 59.99],
                    ['quantity' => 250, 'price' => 99.99],
                    ['quantity' => 500, 'price' => 179.99],
                    ['quantity' => 1000, 'price' => 299.99],
                ]),
                'specifications' => json_encode([
                    'size' => 'A4 (210mm x 297mm), A5',
                    'paper' => '200gsm Gloss, Matte',
                    'finish' => 'Folded, Stapled, Perfect bound',
                    'turnaround' => '3-5 business days'
                ]),
                'design_templates' => json_encode(['corporate', 'product', 'service', 'event', 'business']),
                'sort_order' => 3
            ],

            // Banner Solutions
            [
                'name' => 'Pull-up Banner',
                'slug' => 'pull-up-banner',
                'description' => 'Professional pull-up banners for trade shows, events, and retail displays. Easy setup and high-impact graphics with portable stand.',
                'category' => 'Banner Solutions',
                'subcategory' => 'Portable Displays',
                'base_price' => 89.99,
                'pricing_options' => json_encode([
                    ['quantity' => 1, 'price' => 89.99],
                    ['quantity' => 2, 'price' => 159.99],
                    ['quantity' => 5, 'price' => 349.99],
                ]),
                'specifications' => json_encode([
                    'size' => '850mm x 2000mm',
                    'material' => 'Vinyl with portable stand',
                    'finish' => 'Full-color printing',
                    'turnaround' => '3-5 business days'
                ]),
                'design_templates' => json_encode(['trade-show', 'retail', 'event', 'corporate', 'exhibition']),
                'sort_order' => 4
            ],
            [
                'name' => 'Teardrop Banner',
                'slug' => 'teardrop-banner',
                'description' => 'Eye-catching teardrop banners perfect for events, trade shows, and outdoor displays. Unique shape draws attention and stands out.',
                'category' => 'Banner Solutions',
                'subcategory' => 'Event Displays',
                'base_price' => 79.99,
                'pricing_options' => json_encode([
                    ['quantity' => 1, 'price' => 79.99],
                    ['quantity' => 2, 'price' => 139.99],
                    ['quantity' => 5, 'price' => 299.99],
                ]),
                'specifications' => json_encode([
                    'size' => '600mm x 1800mm',
                    'material' => 'Vinyl with teardrop frame',
                    'finish' => 'Full-color printing',
                    'turnaround' => '3-5 business days'
                ]),
                'design_templates' => json_encode(['event', 'trade-show', 'exhibition', 'outdoor', 'promotional']),
                'sort_order' => 5
            ],
            [
                'name' => 'Vinyl Banner',
                'slug' => 'vinyl-banner',
                'description' => 'Durable outdoor advertising banners made from high-quality vinyl. Perfect for long-term outdoor displays and construction sites.',
                'category' => 'Banner Solutions',
                'subcategory' => 'Outdoor Advertising',
                'base_price' => 69.99,
                'pricing_options' => json_encode([
                    ['quantity' => 1, 'price' => 69.99],
                    ['quantity' => 2, 'price' => 129.99],
                    ['quantity' => 5, 'price' => 279.99],
                ]),
                'specifications' => json_encode([
                    'size' => 'Custom sizes available',
                    'material' => 'Heavy-duty vinyl',
                    'finish' => 'Full-color printing, UV resistant',
                    'turnaround' => '2-3 business days'
                ]),
                'design_templates' => json_encode(['construction', 'real-estate', 'advertising', 'outdoor', 'commercial']),
                'sort_order' => 6
            ],
            [
                'name' => 'Mesh Banner',
                'slug' => 'mesh-banner',
                'description' => 'Wind-resistant mesh banners perfect for outdoor locations with high wind exposure. Allows air to pass through while maintaining visibility.',
                'category' => 'Banner Solutions',
                'subcategory' => 'Wind-Resistant',
                'base_price' => 89.99,
                'pricing_options' => json_encode([
                    ['quantity' => 1, 'price' => 89.99],
                    ['quantity' => 2, 'price' => 159.99],
                    ['quantity' => 5, 'price' => 349.99],
                ]),
                'specifications' => json_encode([
                    'size' => 'Custom sizes available',
                    'material' => 'Mesh vinyl with reinforced edges',
                    'finish' => 'Full-color printing, wind-resistant',
                    'turnaround' => '3-5 business days'
                ]),
                'design_templates' => json_encode(['outdoor', 'construction', 'highway', 'building', 'commercial']),
                'sort_order' => 7
            ],

            // Signage & Display
            [
                'name' => 'Media Wall',
                'slug' => 'media-wall',
                'description' => 'Large format digital printing for media walls and exhibition displays. High-resolution graphics for maximum impact in large spaces.',
                'category' => 'Signage & Display',
                'subcategory' => 'Large Format',
                'base_price' => 199.99,
                'pricing_options' => json_encode([
                    ['quantity' => 1, 'price' => 199.99],
                    ['quantity' => 2, 'price' => 349.99],
                    ['quantity' => 5, 'price' => 799.99],
                ]),
                'specifications' => json_encode([
                    'size' => 'Custom sizes available',
                    'material' => 'High-resolution vinyl',
                    'finish' => 'Full-color printing, high-DPI',
                    'turnaround' => '5-7 business days'
                ]),
                'design_templates' => json_encode(['exhibition', 'trade-show', 'retail', 'corporate', 'event']),
                'sort_order' => 8
            ],
            [
                'name' => 'Lightbox Fabric',
                'slug' => 'lightbox-fabric',
                'description' => 'Illuminated display materials perfect for backlit signage and lightboxes. Translucent fabric that glows when backlit for stunning visual impact.',
                'category' => 'Signage & Display',
                'subcategory' => 'Illuminated',
                'base_price' => 129.99,
                'pricing_options' => json_encode([
                    ['quantity' => 1, 'price' => 129.99],
                    ['quantity' => 2, 'price' => 229.99],
                    ['quantity' => 5, 'price' => 499.99],
                ]),
                'specifications' => json_encode([
                    'size' => 'Custom sizes available',
                    'material' => 'Translucent fabric',
                    'finish' => 'Full-color printing, backlit ready',
                    'turnaround' => '4-6 business days'
                ]),
                'design_templates' => json_encode(['retail', 'hospitality', 'corporate', 'exhibition', 'commercial']),
                'sort_order' => 9
            ],
            [
                'name' => 'Vinyl Stickers',
                'slug' => 'vinyl-stickers',
                'description' => 'Custom adhesive graphics for vehicles, windows, walls, and equipment. High-quality vinyl with various adhesive options for different surfaces.',
                'category' => 'Signage & Display',
                'subcategory' => 'Adhesive Graphics',
                'base_price' => 39.99,
                'pricing_options' => json_encode([
                    ['quantity' => 10, 'price' => 39.99],
                    ['quantity' => 25, 'price' => 69.99],
                    ['quantity' => 50, 'price' => 119.99],
                    ['quantity' => 100, 'price' => 199.99],
                ]),
                'specifications' => json_encode([
                    'size' => 'Custom sizes available',
                    'material' => 'High-quality vinyl',
                    'finish' => 'Full-color printing, various adhesives',
                    'turnaround' => '2-3 business days'
                ]),
                'design_templates' => json_encode(['vehicle', 'window', 'wall', 'equipment', 'custom']),
                'sort_order' => 10
            ],
            [
                'name' => 'Signflute',
                'slug' => 'signflute',
                'description' => 'Corrugated plastic signage perfect for outdoor displays, real estate, and construction sites. Lightweight, durable, and weather-resistant.',
                'category' => 'Signage & Display',
                'subcategory' => 'Corrugated Plastic',
                'base_price' => 49.99,
                'pricing_options' => json_encode([
                    ['quantity' => 1, 'price' => 49.99],
                    ['quantity' => 2, 'price' => 89.99],
                    ['quantity' => 5, 'price' => 199.99],
                ]),
                'specifications' => json_encode([
                    'size' => '600mm x 900mm',
                    'material' => '5mm Corflute',
                    'finish' => 'Full-color printing, weather-resistant',
                    'turnaround' => '2-3 business days'
                ]),
                'design_templates' => json_encode(['real-estate', 'construction', 'advertising', 'outdoor', 'commercial']),
                'sort_order' => 11
            ],
            [
                'name' => 'Alu Panel',
                'slug' => 'alu-panel',
                'description' => 'Aluminum composite panel printing for premium signage and displays. Professional finish with excellent durability and modern appearance.',
                'category' => 'Signage & Display',
                'subcategory' => 'Aluminum Composite',
                'base_price' => 159.99,
                'pricing_options' => json_encode([
                    ['quantity' => 1, 'price' => 159.99],
                    ['quantity' => 2, 'price' => 279.99],
                    ['quantity' => 5, 'price' => 599.99],
                ]),
                'specifications' => json_encode([
                    'size' => 'Custom sizes available',
                    'material' => '3mm Aluminum composite',
                    'finish' => 'Full-color printing, premium finish',
                    'turnaround' => '5-7 business days'
                ]),
                'design_templates' => json_encode(['corporate', 'premium', 'exhibition', 'retail', 'commercial']),
                'sort_order' => 12
            ],
            [
                'name' => 'Yupo Poster',
                'slug' => 'yupo-poster',
                'description' => 'High-quality poster printing on premium Yupo paper. Perfect for art exhibitions, galleries, and premium marketing materials with exceptional print quality.',
                'category' => 'Signage & Display',
                'subcategory' => 'Premium Posters',
                'base_price' => 79.99,
                'pricing_options' => json_encode([
                    ['quantity' => 1, 'price' => 79.99],
                    ['quantity' => 2, 'price' => 139.99],
                    ['quantity' => 5, 'price' => 299.99],
                ]),
                'specifications' => json_encode([
                    'size' => 'A2, A1, A0, Custom sizes',
                    'material' => 'Premium Yupo paper',
                    'finish' => 'Full-color printing, high-resolution',
                    'turnaround' => '3-5 business days'
                ]),
                'design_templates' => json_encode(['art', 'exhibition', 'gallery', 'premium', 'creative']),
                'sort_order' => 13
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
