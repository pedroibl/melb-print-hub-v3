import { Head } from '@inertiajs/react';
import { useState } from 'react';

export default function Layout({ children, title, phone = '0449 598 440', email = 'info@melbourneprinthub.com.au', csrf_token }) {
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);

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
                <meta name="robots" content="index, follow" />
                <meta name="author" content="Melbourne Print Hub" />
                <meta name="description" content="Professional printing services in Melbourne. Fast, local printing solutions for growing businesses." />
                <meta name="keywords" content="printing, Melbourne, business cards, banners, flyers, signage" />
                
                {/* Open Graph Meta Tags */}
                <meta property="og:title" content={title ? `${title} - Melbourne Print Hub` : 'Melbourne Print Hub'} />
                <meta property="og:description" content="Professional printing services in Melbourne. Fast, local printing solutions for growing businesses." />
                <meta property="og:type" content="website" />
                <meta property="og:url" content="https://melbourneprinthub.com.au" />
                <meta property="og:site_name" content="Melbourne Print Hub" />
                
                {/* Twitter Card Meta Tags */}
                <meta name="twitter:card" content="summary_large_image" />
                <meta name="twitter:title" content={title ? `${title} - Melbourne Print Hub` : 'Melbourne Print Hub'} />
                <meta name="twitter:description" content="Professional printing services in Melbourne. Fast, local printing solutions for growing businesses." />
                
                {/* Favicon and Icons */}
                <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
                <link rel="apple-touch-icon" href="/apple-touch-icon.svg" />
                <link rel="manifest" href="/site.webmanifest" />
                
                {/* Preconnect to external domains for performance */}
                <link rel="preconnect" href="https://fonts.googleapis.com" />
                <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="anonymous" />
                <link rel="preconnect" href="https://cdn.jsdelivr.net" />
                <link rel="preconnect" href="https://unpkg.com" />
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
