<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Models\Product;

class PageController extends Controller
{
    /**
     * Display the home page
     */
    public function home()
    {
        $iconMap = [
            'name-business-cards' => 'card',
            'business-cards' => 'card',
            'flyers' => 'document',
            'brochures' => 'document',
            'pull-up-banner' => 'flag',
            'teardrop-banner' => 'flag',
            'vinyl-banner' => 'flag',
            'mesh-banner' => 'flag',
        ];

        $categoryFallback = [
            'Business Essentials' => 'card',
            'Banner Solutions' => 'flag',
        ];

        $services = Product::active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->take(3)
            ->get()
            ->map(function (Product $product) use ($iconMap, $categoryFallback) {
                $icon = $iconMap[$product->slug] ?? $categoryFallback[$product->category] ?? 'document';

                return [
                    'name' => $product->name,
                    'description' => Str::limit($product->description, 120),
                    'icon' => $icon,
                    'price' => 'From $' . number_format($product->base_price, 2),
                ];
            })
            ->values();

        return Inertia::render('Home', [
            'services' => $services,
            'phone' => '0449 598 440',
            'email' => 'info@melbourneprinthub.com.au',
            'csrf_token' => csrf_token()
        ]);
    }

    /**
     * Display the about page
     */
    public function about()
    {
        return Inertia::render('About', [
            'phone' => '0449 598 440',
            'email' => 'info@melbourneprinthub.com.au',
            'hours' => 'Monday to Friday, 08:00 AM to 06:00 PM',
            'csrf_token' => csrf_token()
        ]);
    }

    /**
     * Display the services page
     */
    public function services()
    {
        $services = Product::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        return Inertia::render('Services', [
            'services' => $services,
            'phone' => '0449 598 440',
            'email' => 'info@melbourneprinthub.com.au',
            'csrf_token' => csrf_token()
        ]);
    }
}
