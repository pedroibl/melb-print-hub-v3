import { Head } from '@inertiajs/react';
import { useState } from 'react';

export default function Layout({
    children,
    title,
    meta = {},
    phone = '0449 598 440',
    email = 'info@melbourneprinthub.com.au',
    csrf_token,
}) {
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);

    const socialLinks = [
        {
            name: 'Facebook',
            href: 'https://www.facebook.com/melbprinthub',
            icon: (props) => (
                <svg viewBox="0 0 24 24" fill="currentColor" {...props}>
                    <path d="M22 12.07C22 6.52 17.52 2 11.97 2S2 6.52 2 12.07C2 17.1 5.66 21.24 10.44 22v-7.02H7.9v-2.91h2.54V9.86c0-2.5 1.5-3.89 3.8-3.89 1.1 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.88h2.78l-.44 2.91h-2.34V22C18.34 21.24 22 17.1 22 12.07z" />
                </svg>
            ),
        },
        {
            name: 'Instagram',
            href: 'https://www.facebook.com/melbprinthub',
            icon: ({ className, ...props }) => (
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" className={className} {...props}>
                    <rect x="3.75" y="3.75" width="16.5" height="16.5" rx="4.5" />
                    <circle cx="12" cy="12" r="4" />
                    <circle cx="17" cy="7" r="1" fill="currentColor" stroke="none" />
                </svg>
            ),
        },
        {
            name: 'LinkedIn',
            href: 'https://www.facebook.com/melbprinthub',
            icon: (props) => (
                <svg viewBox="0 0 24 24" fill="currentColor" {...props}>
                    <path d="M4.98 3.5a2.5 2.5 0 11-.02 5 2.5 2.5 0 01.02-5zM3 9h4v12H3zM10 9h3.6v1.71h.05c.5-.95 1.72-1.95 3.55-1.95 3.8 0 4.5 2.5 4.5 5.7V21h-4v-5.6c0-1.34-.03-3.06-1.87-3.06-1.88 0-2.17 1.47-2.17 2.96V21h-4z" />
                </svg>
            ),
        },
        {
            name: 'Pinterest',
            href: 'https://www.facebook.com/melbprinthub',
            icon: (props) => (
                <svg viewBox="0 0 24 24" fill="currentColor" {...props}>
                    <path d="M12.04 2c-5.23 0-7.9 3.74-7.9 6.85 0 1.88.71 3.55 2.24 4.17.25.1.48 0 .55-.27.05-.19.18-.68.24-.88.08-.27.05-.37-.15-.61-.44-.52-.72-1.2-.72-2.16 0-2.78 2.09-5.27 5.44-5.27 2.97 0 4.6 1.82 4.6 4.25 0 3.02-1.34 5.58-3.32 5.58-1.1 0-1.93-.9-1.66-2.01.32-1.35.94-2.8.94-3.77 0-.87-.47-1.6-1.45-1.6-1.15 0-2.08 1.19-2.08 2.79 0 1.02.35 1.71.35 1.71s-1.19 5.05-1.4 5.94c-.42 1.77-.06 3.95-.03 4.17 0 .13.18.17.25.07.1-.13 1.3-1.61 1.71-3.1.12-.43.66-2.67.66-2.67.33.63 1.3 1.18 2.33 1.18 3.07 0 5.15-2.8 5.15-6.53C19.96 5.16 16.98 2 12.04 2z" />
                </svg>
            ),
        },
    ];

    const {
        description = 'Fast, high-quality printing services in Melbourne for business cards, flyers, brochures, banners, and signage.',
        keywords = 'printing services melbourne, business card printing melbourne, flyer printing melbourne, banner printing melbourne',
        canonical = 'https://melbourneprinthub.com.au',
        ogImage = 'https://melbourneprinthub.com.au/og-image.jpg',
        type = 'website',
        structuredData = [],
        noindex = false,
    } = meta;

    const localBusinessSchema = {
        '@context': 'https://schema.org',
        '@type': 'LocalBusiness',
        name: 'Melbourne Print Hub',
        url: 'https://melbourneprinthub.com.au',
        telephone: phone,
        email,
        image: 'https://melbourneprinthub.com.au/images/print-shop.jpg',
        priceRange: '$$',
        address: {
            '@type': 'PostalAddress',
            streetAddress: '58 Leonard Avenue',
            addressLocality: 'Noble Park',
            addressRegion: 'VIC',
            postalCode: '3174',
            addressCountry: 'AU',
        },
        openingHoursSpecification: [
            {
                '@type': 'OpeningHoursSpecification',
                dayOfWeek: [
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday',
                ],
                opens: '09:00',
                closes: '17:30',
            },
        ],
        sameAs: socialLinks.map((link) => link.href),
    };

    return (
        <>
            <Head title={title ? `${title} - Melbourne Print Hub` : 'Melbourne Print Hub'}>
                {/* CSRF Token */}
                <meta name="csrf-token" content={csrf_token} />
                
                {/* Security Meta Tags */}
                <meta httpEquiv="X-Frame-Options" content="DENY" />
                <meta httpEquiv="X-Content-Type-Options" content="nosniff" />
                <meta httpEquiv="X-XSS-Protection" content="1; mode=block" />
                <meta name="referrer" content="strict-origin-when-cross-origin" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <meta name="robots" content={noindex ? 'noindex, nofollow' : 'index, follow'} />
                <meta name="author" content="Melbourne Print Hub" />
                <meta name="description" content={description} />
                <meta name="keywords" content={keywords} />

                {/* Open Graph Meta Tags */}
                <meta property="og:title" content={title ? `${title} - Melbourne Print Hub` : 'Melbourne Print Hub'} />
                <meta property="og:description" content={description} />
                <meta property="og:type" content={type} />
                <meta property="og:url" content={canonical} />
                <meta property="og:site_name" content="Melbourne Print Hub" />
                {ogImage && <meta property="og:image" content={ogImage} />}

                {/* Twitter Card Meta Tags */}
                <meta name="twitter:card" content="summary_large_image" />
                <meta name="twitter:title" content={title ? `${title} - Melbourne Print Hub` : 'Melbourne Print Hub'} />
                <meta name="twitter:description" content={description} />
                {ogImage && <meta name="twitter:image" content={ogImage} />}

                {/* Favicon and Icons */}
                <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
                <link rel="apple-touch-icon" href="/apple-touch-icon.svg" />
                <link rel="manifest" href="/site.webmanifest" />
                <link rel="canonical" href={canonical} />

                {/* Preconnect to external domains for performance */}
                <link rel="preconnect" href="https://fonts.googleapis.com" />
                <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="anonymous" />
                <link rel="preconnect" href="https://cdn.jsdelivr.net" />
                <link rel="preconnect" href="https://unpkg.com" />

                <script type="application/ld+json">{JSON.stringify(localBusinessSchema)}</script>
                {structuredData.map((schema, index) => (
                    <script key={index} type="application/ld+json">
                        {JSON.stringify(schema)}
                    </script>
                ))}
            </Head>
            
            <div className="min-h-screen bg-gray-50">
                {/* Header */}
                <header className="bg-white shadow-sm sticky top-0 z-50">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex justify-between items-center py-6">
                            <div className="flex items-center">
                                <h1 className="text-2xl font-bold text-gray-900">
                                    <a href="/" className="hover:text-blue-600 transition-colors">
                                        Melbourne Print Hub
                                    </a>
                                </h1>
                            </div>
                            
                            {/* Desktop Navigation */}
                            <nav className="hidden md:flex items-center space-x-8">
                                <a href="/" className="text-gray-600 hover:text-blue-600 transition-colors">Home</a>
                                <a href="/about" className="text-gray-600 hover:text-blue-600 transition-colors">About</a>
                                <a href="/services" className="text-gray-600 hover:text-blue-600 transition-colors">Services</a>
                                <a href="/get-quote" className="text-gray-600 hover:text-blue-600 transition-colors">Get Quote</a>
                                <a href="/contact" className="text-gray-600 hover:text-blue-600 transition-colors">Contact</a>
                                
                                {/* Simple HTML WhatsApp Button */}
                                <button 
                                    onClick={() => {
                                        const phone = '+61449598440';
                                        const message = "Hi! I have a printing question. Can you help?";
                                        const cleanPhone = phone.replace(/[^0-9+]/g, '');
                                        const encodedMessage = encodeURIComponent(message);
                                        const url = `https://wa.me/${cleanPhone}?text=${encodedMessage}`;
                                        window.open(url, '_blank');
                                    }}
                                    className="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition-colors"
                                >
                                    Chat on WhatsApp
                                </button>
                            </nav>

                            {/* Mobile menu button */}
                            <button
                                className="md:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100"
                                onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
                            >
                                <svg className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                        </div>

                        {/* Mobile Navigation */}
                        {isMobileMenuOpen && (
                            <nav className="md:hidden py-4 border-t border-gray-200">
                                <div className="flex flex-col space-y-3">
                                    <a href="/" className="text-gray-600 hover:text-blue-600 transition-colors py-2">Home</a>
                                    <a href="/about" className="text-gray-600 hover:text-blue-600 transition-colors py-2">About</a>
                                    <a href="/services" className="text-gray-600 hover:text-blue-600 transition-colors py-2">Services</a>
                                    <a href="/get-quote" className="text-gray-600 hover:text-blue-600 transition-colors py-2">Get Quote</a>
                                    <a href="/contact" className="text-gray-600 hover:text-blue-600 transition-colors py-2">Contact</a>
                                    
                                    {/* Mobile WhatsApp Button */}
                                    <button 
                                        onClick={() => {
                                            const phone = '+61449598440';
                                            const message = "Hi! I have a printing question. Can you help?";
                                            const cleanPhone = phone.replace(/[^0-9+]/g, '');
                                            const encodedMessage = encodeURIComponent(message);
                                            const url = `https://wa.me/${cleanPhone}?text=${encodedMessage}`;
                                            window.open(url, '_blank');
                                        }}
                                        className="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition-colors text-center"
                                    >
                                        💬 Chat on WhatsApp
                                    </button>
                                </div>
                            </nav>
                        )}
                    </div>
                </header>

                {/* Main Content */}
                <main>
                    {children}
                </main>

                {/* Enhanced Footer */}
                <footer className="bg-gray-800 text-white">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                        {/* Service Categories Grid */}
                        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
                            {/* Business Essentials */}
                            <div className="space-y-4">
                                <h3 className="text-lg font-semibold text-blue-400 mb-4 flex items-center">
                                    <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Business Essentials
                                </h3>
                                <p className="text-gray-300 text-sm mb-4">
                                    Core printing services for everyday business needs
                                </p>
                                <ul className="space-y-2">
                                    <li>
                                        <a href="/services" className="text-gray-300 hover:text-white transition-colors text-sm flex items-center">
                                            <svg className="w-3 h-3 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                            </svg>
                                            Name/Business Cards
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/services" className="text-gray-300 hover:text-white transition-colors text-sm flex items-center">
                                            <svg className="w-3 h-3 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                            </svg>
                                            Flyers
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/services" className="text-gray-300 hover:text-white transition-colors text-sm flex items-center">
                                            <svg className="w-3 h-3 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                            </svg>
                                            Brochures
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            {/* Banner Solutions */}
                            <div className="space-y-4">
                                <h3 className="text-lg font-semibold text-green-400 mb-4 flex items-center">
                                    <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                                    </svg>
                                    Banner Solutions
                                </h3>
                                <p className="text-gray-300 text-sm mb-4">
                                    Professional banner and display solutions for events and marketing
                                </p>
                                <ul className="space-y-2">
                                    <li>
                                        <a href="/services" className="text-gray-300 hover:text-white transition-colors text-sm flex items-center">
                                            <svg className="w-3 h-3 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                            </svg>
                                            Pull-up Banner
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/services" className="text-gray-300 hover:text-white transition-colors text-sm flex items-center">
                                            <svg className="w-3 h-3 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                            </svg>
                                            Teardrop Banner
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/services" className="text-gray-300 hover:text-white transition-colors text-sm flex items-center">
                                            <svg className="w-3 h-3 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                            </svg>
                                            Vinyl Banner
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/services" className="text-gray-300 hover:text-white transition-colors text-sm flex items-center">
                                            <svg className="w-3 h-3 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                            </svg>
                                            Mesh Banner
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            {/* Signage & Display */}
                            <div className="space-y-4">
                                <h3 className="text-lg font-semibold text-purple-400 mb-4 flex items-center">
                                    <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    Signage & Display
                                </h3>
                                <p className="text-gray-300 text-sm mb-4">
                                    Premium signage and display materials for maximum impact
                                </p>
                                <ul className="space-y-2">
                                    <li>
                                        <a href="/services" className="text-gray-300 hover:text-white transition-colors text-sm flex items-center">
                                            <svg className="w-3 h-3 mr-2 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                            </svg>
                                            Alu Panel
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/services" className="text-gray-300 hover:text-white transition-colors text-sm flex items-center">
                                            <svg className="w-3 h-3 mr-2 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                            </svg>
                                            Lightbox Fabric
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/services" className="text-gray-300 hover:text-white transition-colors text-sm flex items-center">
                                            <svg className="w-3 h-3 mr-2 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                            </svg>
                                            Media Wall
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/services" className="text-gray-300 hover:text-white transition-colors text-sm flex items-center">
                                            <svg className="w-3 h-3 mr-2 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                            </svg>
                                            Signflute
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/services" className="text-gray-300 hover:text-white transition-colors text-sm flex items-center">
                                            <svg className="w-3 h-3 mr-2 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                            </svg>
                                            Vinyl Stickers
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/services" className="text-gray-300 hover:text-white transition-colors text-sm flex items-center">
                                            <svg className="w-3 h-3 mr-2 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                            </svg>
                                            Yupo Poster
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        {/* Contact & Quick Links Row */}
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                            {/* Company Info */}
                            <div className="space-y-4">
                                <h3 className="text-lg font-semibold text-white mb-4">Melbourne Print Hub</h3>
                                <p className="text-gray-300 text-sm">
                                    Professional printing solutions for Melbourne businesses. 
                                    Fast turnaround and competitive pricing.
                                </p>
                                <div className="space-y-2 text-sm text-gray-300">
                                    <p><strong>Address:</strong> 58 Leonard Avenue, Noble Park VIC 3174</p>
                                    <p><strong>Opening Hours:</strong> Monday to Friday, 08:00 AM to 06:00 PM</p>
                                    <p><strong>Phone:</strong> {phone}</p>
                                    <p><strong>Email:</strong> {email}</p>
                                    <div className="pt-2">
                                        <button 
                                            onClick={() => {
                                                const phone = '+61449598440';
                                                const message = "Hi! I have a printing question. Can you help?";
                                                const cleanPhone = phone.replace(/[^0-9+]/g, '');
                                                const encodedMessage = encodeURIComponent(message);
                                                const url = `https://wa.me/${cleanPhone}?text=${encodedMessage}`;
                                                window.open(url, '_blank');
                                            }}
                                            className="text-green-400 hover:text-green-300 transition-colors text-sm"
                                        >
                                            💬 Chat on WhatsApp
                                        </button>
                                    </div>
                                </div>
                                <div className="pt-4">
                                    <h4 className="text-sm font-semibold text-white uppercase tracking-wide">Connect With Us</h4>
                                    <div className="mt-3 flex items-center space-x-4">
                                        {socialLinks.map(({ name, href, icon: Icon }) => (
                                            <a
                                                key={name}
                                                href={href}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="text-gray-300 hover:text-white transition-colors"
                                                aria-label={name}
                                            >
                                                <Icon className="h-5 w-5" aria-hidden="true" />
                                                <span className="sr-only">{name}</span>
                                            </a>
                                        ))}
                                    </div>
                                </div>
                            </div>

                            {/* Quick Links */}
                            <div className="space-y-4">
                                <h4 className="text-lg font-semibold text-white mb-4">Quick Links</h4>
                                <ul className="space-y-2">
                                    <li><a href="/" className="text-gray-300 hover:text-white transition-colors text-sm">Home</a></li>
                                    <li><a href="/about" className="text-gray-300 hover:text-white transition-colors text-sm">About</a></li>
                                    <li><a href="/services" className="text-gray-300 hover:text-white transition-colors text-sm">Services</a></li>
                                    <li><a href="/get-quote" className="text-gray-300 hover:text-white transition-colors text-sm">Get Quote</a></li>
                                    <li><a href="/contact" className="text-gray-300 hover:text-white transition-colors text-sm">Contact</a></li>
                                </ul>
                            </div>

                            {/* Call to Action */}
                            <div className="space-y-4">
                                <h4 className="text-lg font-semibold text-white mb-4">Ready to Get Started?</h4>
                                <p className="text-gray-300 text-sm mb-4">
                                    Get your professional printing project started today with a free quote.
                                </p>
                                <a 
                                    href="/get-quote" 
                                    className="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium"
                                >
                                    Get Free Quote
                                </a>
                            </div>
                        </div>

                        {/* Bottom Bar */}
                        <div className="border-t border-gray-700 pt-8 text-center text-gray-300">
                            <p className="text-sm">&copy; {new Date().getFullYear()} Melbourne Print Hub. All rights reserved.</p>
                            <p className="text-xs mt-2">Professional printing solutions for Melbourne businesses</p>
                        </div>
                    </div>
                </footer>

                {/* Mobile WhatsApp Button - Sticky (Always visible on mobile) */}
                <div className="fixed bottom-6 right-6 z-[9999] md:hidden">
                    <button 
                        onClick={() => {
                            const phone = '+61449598440';
                            const message = "Hi! I have a printing question. Can you help?";
                            const cleanPhone = phone.replace(/[^0-9+]/g, '');
                            const encodedMessage = encodeURIComponent(message);
                            const url = `https://wa.me/${cleanPhone}?text=${encodedMessage}`;
                            console.log('Opening WhatsApp:', url);
                            window.open(url, '_blank');
                        }}
                        className="w-16 h-16 bg-green-500 text-white rounded-full shadow-2xl hover:shadow-3xl hover:bg-green-600 transition-all duration-200 flex items-center justify-center relative z-10"
                        aria-label="Chat on WhatsApp"
                        style={{ touchAction: 'manipulation' }}
                    >
                        <svg className="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.263.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                        </svg>
                    </button>
                    
                    {/* Pulse animation ring */}
                    <div className="absolute inset-0 w-16 h-16 rounded-full bg-green-500 opacity-20 animate-ping pointer-events-none"></div>
                </div>
            </div>
        </>
    );
}
