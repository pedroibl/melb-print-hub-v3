import Layout from '@/Components/Layout';
import { useEffect, useState, useMemo } from 'react';

const HERO_SLIDES = [
    {
        image: '/images/business-cards.jpg',
        title: 'Business Cards That Stand Out',
        description: 'Premium 350gsm stocks with matte, gloss, or velvet laminate. Perfect for networking across Melbourne.',
        ctaLabel: 'Explore Business Cards',
        ctaHref: '/services',
        alt: 'Stack of freshly printed business cards with clean typography and brand colours',
    },
    {
        image: '/images/flyers.jpg',
        title: 'Flyers & Brochures for Every Campaign',
        description: 'Full-colour flyers, folded brochures, and direct mail pieces with express printing and delivery.',
        ctaLabel: 'See Flyer Options',
        ctaHref: '/services',
        alt: 'Colourful brochures and flyers laid out on a studio table',
    },
    {
        image: '/images/banners.jpg',
        title: 'Large Format Banners & Signage',
        description: 'Pull-up banners, event backdrops, and outdoor signage printed with durable, UV-stable inks.',
        ctaLabel: 'View Banner Solutions',
        ctaHref: '/services',
        alt: 'Event banners displayed at an indoor exhibition',
    },
    {
        image: '/images/corflute-signs.jpg',
        title: 'Corflute & Site Signage',
        description: 'Weather-resistant corflute signage for construction, events, and retail promotions across Melbourne.',
        ctaLabel: 'Order Signage',
        ctaHref: '/services',
        alt: 'Corflute signs stacked together ready for delivery',
    },
    {
        image: '/images/melbourne-cityscape-1.jpg',
        title: 'Local Production in the Heart of Melbourne',
        description: 'Same-day print and courier options from our CBD location to keep your deadlines on track.',
        ctaLabel: 'Request a Quote',
        ctaHref: '/get-quote',
        alt: 'Melbourne city skyline at dusk highlighting local service area',
    },
    {
        image: '/images/melbourne-cityscape-2.jpg',
        title: 'Trusted by Melbourne Businesses',
        description: 'From hospitality to real estate, we support local brands with quality printing and personal service.',
        ctaLabel: 'Work With Us',
        ctaHref: '/contact',
        alt: 'Melbourne laneway with vibrant signage and lights',
    },
];

export default function Home({ services, phone, email, csrf_token }) {
    const slides = useMemo(() => HERO_SLIDES, []);
    const [currentSlide, setCurrentSlide] = useState(0);

    useEffect(() => {
        const interval = setInterval(() => {
            setCurrentSlide((prev) => (prev + 1) % slides.length);
        }, 6000);

        return () => clearInterval(interval);
    }, [slides.length]);

    const goToSlide = (index) => {
        setCurrentSlide((index + slides.length) % slides.length);
    };

    const activeSlide = slides[currentSlide];

    return (
        <Layout
            title="Printing Services in Melbourne"
            csrf_token={csrf_token}
            meta={{
                description: 'Melbourne Print Hub delivers fast, high-quality printing services in Melbourne including business cards, flyers, brochures, banners, and signage with same-day options.',
                keywords: 'printing services melbourne, same-day printing melbourne, business card printing melbourne, flyer printing melbourne, banner printing melbourne',
                canonical: 'https://melbourneprinthub.com.au/',
                structuredData: [
                    {
                        '@context': 'https://schema.org',
                        '@type': 'WebPage',
                        name: 'Melbourne Print Hub | Printing Services in Melbourne',
                        description: 'Local Melbourne print specialists offering business cards, flyers, brochures, banners, signage, and express printing services.',
                        url: 'https://melbourneprinthub.com.au/',
                    },
                ],
            }}
        >
            {/* Hero Carousel */}
            <section className="relative text-white">
                <div className="relative min-h-[420px] sm:min-h-[480px] md:min-h-[560px] overflow-hidden">
                    {slides.map((slide, index) => (
                        <div
                            key={slide.image}
                            className={`absolute inset-0 transition-opacity duration-700 ease-in-out ${
                                index === currentSlide ? 'opacity-100' : 'opacity-0'
                            }`}
                            aria-hidden={index !== currentSlide}
                        >
                            <img
                                src={slide.image}
                                alt={slide.alt}
                                className="w-full h-full object-cover"
                                loading={index === currentSlide ? 'eager' : 'lazy'}
                            />
                            <div className="absolute inset-0 bg-gradient-to-r from-blue-900/80 via-blue-900/60 to-blue-900/40" />
                        </div>
                    ))}

                    <div className="relative h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col justify-center items-center md:items-start">
                        <div className="w-full max-w-xl md:max-w-3xl text-center md:text-left">
                            <h1 className="text-3xl sm:text-4xl md:text-5xl font-bold mb-4 sm:mb-6 drop-shadow-lg">
                                {activeSlide.title}
                            </h1>
                            <p className="text-base sm:text-lg md:text-xl mb-6 sm:mb-8 text-blue-100">
                                {activeSlide.description}
                            </p>
                            <div className="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center md:justify-start">
                                <a
                                    href={activeSlide.ctaHref}
                                    className="bg-white text-blue-700 px-5 py-3 text-base sm:px-6 sm:py-3.5 sm:text-lg rounded-lg font-semibold hover:bg-blue-50 transition-colors inline-flex items-center justify-center"
                                >
                                    {activeSlide.ctaLabel}
                                </a>
                                <a
                                    href="/get-quote"
                                    className="border-2 border-white text-white px-5 py-3 text-base sm:px-6 sm:py-3.5 sm:text-lg rounded-lg font-semibold hover:bg-white/10 transition-colors inline-flex items-center justify-center"
                                >
                                    Request a Quote
                                </a>
                            </div>
                        </div>

                        {/* Carousel Controls */}
                        <div className="absolute inset-x-0 bottom-6">
                            <div className="max-w-xl md:max-w-3xl mx-auto flex items-center justify-center sm:justify-between gap-4 px-4 sm:px-0">
                                <button
                                    type="button"
                                    onClick={() => goToSlide(currentSlide - 1)}
                                    className="hidden sm:flex items-center justify-center w-11 h-11 rounded-full bg-white/20 hover:bg-white/30 transition-colors"
                                    aria-label="Previous slide"
                                >
                                    <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>

                                <div className="flex items-center space-x-2">
                                    {slides.map((_, index) => (
                                        <button
                                            key={index}
                                            type="button"
                                            onClick={() => goToSlide(index)}
                                            className={`h-2.5 rounded-full transition-all duration-300 ${
                                                index === currentSlide ? 'w-8 bg-white' : 'w-3 bg-white/50 hover:bg-white/80'
                                            }`}
                                            aria-label={`Go to slide ${index + 1}`}
                                        />
                                    ))}
                                </div>

                                <button
                                    type="button"
                                    onClick={() => goToSlide(currentSlide + 1)}
                                    className="hidden sm:flex items-center justify-center w-11 h-11 rounded-full bg-white/20 hover:bg-white/30 transition-colors"
                                    aria-label="Next slide"
                                >
                                    <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Service Overview */}
            <section className="py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">
                            Melbourne Printing Services for Every Campaign
                        </h2>
                        <p className="text-xl text-gray-600 max-w-2xl mx-auto">
                            From print collateral for networking events to large format signage for expos, we combine premium stocks, vibrant inks, and fast delivery across metropolitan Melbourne.
                        </p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                        {services.map((service, index) => (
                            <div key={index} className="bg-white rounded-lg shadow-lg p-8 text-center hover:shadow-xl transition-shadow">
                                <div className="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <svg className="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        {service.icon === 'card' && (
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        )}
                                        {service.icon === 'document' && (
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        )}
                                        {service.icon === 'flag' && (
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                                        )}
                                    </svg>
                                </div>
                                <h3 className="text-xl font-semibold text-gray-900 mb-3">
                                    {service.name}
                                </h3>
                                <p className="text-gray-600 mb-4">
                                    {service.description}
                                </p>
                                <div className="text-blue-600 font-semibold mb-4">
                                    {service.price}
                                </div>
                                <a 
                                    href="/get-quote" 
                                    className="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors"
                                >
                                    Get Quote
                                </a>
                            </div>
                        ))}
                    </div>

                    <div className="text-center mt-12">
                        <a 
                            href="/services" 
                            className="inline-block bg-gray-800 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-900 transition-colors"
                        >
                            View All Services
                        </a>
                    </div>
                </div>
            </section>

            {/* Local Advantage Section */}
            <section className="py-16 bg-white">
                <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                        <div>
                            <h2 className="text-3xl font-bold text-gray-900 mb-4">Proudly Serving Melbourne Businesses</h2>
                            <p className="text-lg text-gray-600 mb-4">
                                We work with cafes on Chapel Street, real estate agencies in Fitzroy, event coordinators in Docklands, and local councils in the CBD. Our production team understands the deadlines and finish requirements that Melbourne businesses rely on.
                            </p>
                            <p className="text-lg text-gray-600 mb-4">
                                Whether you’re launching a pop-up store or preparing for a conference at the Melbourne Convention Centre, our same-day printing and delivery options keep your campaign on track.
                            </p>
                            <p className="text-lg text-gray-600">
                                Every order includes proofing support, stock recommendations, and finishing guidance so your print collateral represents your brand perfectly.
                            </p>
                        </div>
                        <div className="bg-gray-100 rounded-xl p-8 shadow-lg">
                            <h3 className="text-2xl font-semibold text-gray-900 mb-4">Same-Day Printing Checklist</h3>
                            <ul className="space-y-3 text-gray-600">
                                <li className="flex items-start">
                                    <span className="text-blue-500 font-bold mr-2">•</span>
                                    Approved artwork received before 11am (PDF with bleed and crop marks)
                                </li>
                                <li className="flex items-start">
                                    <span className="text-blue-500 font-bold mr-2">•</span>
                                    Select from our express stocks: 350gsm artboard, 150gsm gloss, or tear-resistant vinyl
                                </li>
                                <li className="flex items-start">
                                    <span className="text-blue-500 font-bold mr-2">•</span>
                                    Choose click-and-collect from Melbourne CBD or metro courier delivery
                                </li>
                                <li className="flex items-start">
                                    <span className="text-blue-500 font-bold mr-2">•</span>
                                    Receive a production SMS update once your order is in finishing
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            {/* Why Choose Us */}
            <section className="py-16 bg-gray-100">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">
                            Why Choose Melbourne Print Hub?
                        </h2>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div className="text-center">
                            <div className="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg className="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <h3 className="text-xl font-semibold text-gray-900 mb-2">Fast Turnaround</h3>
                            <p className="text-gray-600">24-48 hour express options available</p>
                        </div>

                        <div className="text-center">
                            <div className="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg className="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                            </div>
                            <h3 className="text-xl font-semibold text-gray-900 mb-2">Local Service</h3>
                            <p className="text-gray-600">Melbourne-based with personal support</p>
                        </div>

                        <div className="text-center">
                            <div className="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg className="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4" />
                                </svg>
                            </div>
                            <h3 className="text-xl font-semibold text-gray-900 mb-2">Design Support</h3>
                            <p className="text-gray-600">Expert design assistance included</p>
                        </div>
                    </div>
                </div>
            </section>

            {/* CTA Section */}
            <section className="py-16 bg-blue-600 text-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 className="text-3xl font-bold mb-4">
                        Ready to Get Started?
                    </h2>
                    <p className="text-xl mb-8 max-w-2xl mx-auto">
                        Get a tailored quote back in under 2 hours. Our Melbourne print specialists will confirm artwork requirements, stock recommendations, and delivery timelines with you instantly.
                    </p>
                    <div className="flex flex-col sm:flex-row gap-4 justify-center">
                        <a 
                            href="/get-quote" 
                            className="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition-colors inline-block"
                        >
                            Get Free Quote
                        </a>
                        <a 
                            href="/contact" 
                            className="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white hover:text-blue-600 transition-colors inline-block"
                        >
                            Contact Us
                        </a>
                    </div>
                    <div className="mt-8 text-center">
                        <p className="text-lg">
                            <strong>Call us:</strong> {phone} | <strong>Email:</strong> {email}
                        </p>
                        <p className="text-sm mt-2 text-blue-100">
                            Melbourne Print Hub · 58 Leonard Avenue, Noble Park VIC 3174
                        </p>
                    </div>
                </div>
            </section>
        </Layout>
    );
}
