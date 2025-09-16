<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Product;

class PageController extends Controller
{
    /**
     * Display the home page
     */
    public function home()
    {
        $services = [
            [
                'name' => 'Business Cards',
                'description' => 'Professional business cards with high-quality printing',
                'icon' => 'card',
                'price' => 'From $29.99'
            ],
            [
                'name' => 'Flyers & Brochures',
                'description' => 'Eye-catching marketing materials for your business',
                'icon' => 'document',
                'price' => 'From $39.99'
            ],
            [
                'name' => 'Banners & Signs',
                'description' => 'Large format printing for events and displays',
                'icon' => 'flag',
                'price' => 'From $89.99'
            ]
        ];

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
