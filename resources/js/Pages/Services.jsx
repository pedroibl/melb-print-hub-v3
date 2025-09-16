import Layout from '@/Components/Layout';
import { useState } from 'react';

export default function Services({ services, phone, email, csrf_token }) {
    const [selectedCategory, setSelectedCategory] = useState('all');
    
    const categories = ['all', ...Object.keys(services)];
    
    const filteredServices = selectedCategory === 'all' 
        ? Object.values(services).flat()
        : services[selectedCategory] || [];

    // Category descriptions for better context
    const categoryDescriptions = {
        'Business Essentials': 'Core printing services for everyday business needs',
        'Banner Solutions': 'Professional banner and display solutions for events and marketing',
        'Signage & Display': 'Premium signage and display materials for maximum impact'
    };

    const productStructuredData = [
        {
            '@context': 'https://schema.org',
            '@type': 'Product',
            name: 'Business Cards Printing Melbourne',
            description: 'High-quality 350gsm artboard business cards with matte or gloss laminate and same-day turnaround in Melbourne.',
            brand: 'Melbourne Print Hub',
            url: 'https://melbourneprinthub.com.au/services/business-cards',
            offers: {
                '@type': 'Offer',
                availability: 'https://schema.org/InStock',
                priceCurrency: 'AUD',
                price: '49.00',
            },
        },
        {
            '@context': 'https://schema.org',
            '@type': 'Product',
            name: 'Flyer & Brochure Printing Melbourne',
            description: 'Premium flyer and brochure printing with full-colour coverage, scored folds, and express delivery across Melbourne.',
            brand: 'Melbourne Print Hub',
            url: 'https://melbourneprinthub.com.au/services/flyers-and-brochures',
            offers: {
                '@type': 'Offer',
                availability: 'https://schema.org/InStock',
                priceCurrency: 'AUD',
                price: '69.00',
            },
        },
        {
            '@context': 'https://schema.org',
            '@type': 'Product',
            name: 'Wide Format & Banner Printing Melbourne',
            description: 'Durable vinyl banners, pull-up banners, and media walls printed with UV stable inks for events and promotions.',
            brand: 'Melbourne Print Hub',
            url: 'https://melbourneprinthub.com.au/services/banners',
            offers: {
                '@type': 'Offer',
                availability: 'https://schema.org/InStock',
                priceCurrency: 'AUD',
                price: '129.00',
            },
        },
    ];

    return (
        <Layout
            title="Printing Services in Melbourne"
            csrf_token={csrf_token}
            meta={{
                description: 'Discover Melbourne Print Hub’s full range of printing services including business cards, flyers, brochures, banners, exhibition displays, and same-day printing.',
                keywords: 'business cards printing melbourne, flyer printing melbourne, brochure printing melbourne, banner printing melbourne, signage melbourne',
                canonical: 'https://melbourneprinthub.com.au/services',
                structuredData: productStructuredData,
            }}
        >
            {/* Hero Section */}
            <section className="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h1 className="text-4xl md:text-6xl font-bold mb-6">
                        Melbourne's Complete Printing Solutions
                    </h1>
                    <p className="text-xl md:text-2xl max-w-3xl mx-auto">
                        Professional printing services for every business need—business cards, flyers, brochures, banners, signage, and custom displays produced in Melbourne with quality control and genuine local support.
                    </p>
                    <div className="mt-8">
                        <a 
                            href="/get-quote" 
                            className="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition-colors inline-block"
                        >
                            Get Your Quote Today
                        </a>
                    </div>
                </div>
            </section>

            {/* Service Copy Section */}
            <section className="py-16 bg-white">
                <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
                    <div>
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Business Card Printing in Melbourne</h2>
                        <p className="text-lg text-gray-600">
                            Put your brand in the right hands with expertly finished business cards. Choose from 350gsm artboard, soft-touch matte laminate, velvet laminate, or recycled stocks. We can spot UV, emboss, or foil your logo, and we check every artwork for bleed, crop marks, and safe zones before going to press. Need it in a hurry? Approve your proof before 11am and collect from Melbourne CBD the same afternoon.
                        </p>
                    </div>
                    <div>
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Flyers, Brochures &amp; Direct Mail</h2>
                        <p className="text-lg text-gray-600">
                            Promote events, menus, and corporate campaigns with vivid double-sided flyers or folded brochures. We provide 150gsm and 170gsm gloss or satin paper as standard, with heavier 200gsm options for premium finishes. Add score folding, perforations, or direct-mail bundling so your marketing collateral is ready for distribution anywhere in Melbourne.
                        </p>
                    </div>
                    <div>
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Large Format Banners &amp; Event Signage</h2>
                        <p className="text-lg text-gray-600">
                            Make a statement with outdoor and indoor signage built to withstand Australian conditions. Our vinyl banners, mesh fence wraps, pull-up banners, media walls, corflutes, and A-frame signs are printed with UV-stable inks and finished with reinforced edges, eyelets, or pull-up hardware. We pre-flight panel dimensions and supply install guides for events, markets, and exhibitions across Melbourne.
                        </p>
                    </div>
                </div>
            </section>

            {/* Category Filter */}
            <section className="py-8 bg-white border-b sticky top-0 z-10">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-wrap gap-2 justify-center">
                        {categories.map(category => (
                            <button
                                key={category}
                                onClick={() => setSelectedCategory(category)}
                                className={`px-6 py-3 rounded-lg text-sm font-medium transition-colors ${
                                    selectedCategory === category
                                        ? 'bg-blue-600 text-white shadow-lg'
                                        : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                                }`}
                            >
                                {category === 'all' ? 'All Services' : category}
                            </button>
                        ))}
                    </div>
                </div>
            </section>

            {/* Category Description */}
            {selectedCategory !== 'all' && (
                <section className="py-6 bg-gray-50">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                        <h2 className="text-2xl font-bold text-gray-900 mb-2">
                            {selectedCategory}
                        </h2>
                        <p className="text-lg text-gray-600 max-w-2xl mx-auto">
                            {categoryDescriptions[selectedCategory] || 'Professional printing solutions for your business'}
                        </p>
                    </div>
                </section>
            )}

            {/* Services Grid */}
            <section className="py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {filteredServices.length > 0 ? (
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            {filteredServices.map((service) => (
                                <div key={service.id} className="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
                                    <div className="p-6">
                                        <div className="mb-4">
                                            <h3 className="text-xl font-semibold text-gray-900 mb-2">
                                                {service.name}
                                            </h3>
                                            <div className="flex gap-2 mb-3">
                                                {service.subcategory && (
                                                    <span className="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                                        {service.subcategory}
                                                    </span>
                                                )}
                                                <span className="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                                    {service.category}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <p className="text-gray-600 text-sm mb-4 line-clamp-3">
                                            {service.description}
                                        </p>
                                        
                                        <div className="mb-4">
                                            <span className="text-2xl font-bold text-blue-600">
                                                From ${service.base_price}
                                            </span>
                                        </div>
                                        
                                        {service.pricing_options && (
                                            <div className="mb-4">
                                                <h4 className="text-sm font-medium text-gray-900 mb-2">Pricing Options:</h4>
                                                <div className="space-y-1">
                                                    {JSON.parse(service.pricing_options).map((option, index) => (
                                                        <div key={index} className="flex justify-between text-sm">
                                                            <span>{option.quantity} {option.quantity === 1 ? 'piece' : 'pieces'}</span>
                                                            <span className="font-medium">${option.price}</span>
                                                        </div>
                                                    ))}
                                                </div>
                                            </div>
                                        )}
                                        
                                        {service.specifications && (
                                            <div className="mb-4">
                                                <h4 className="text-sm font-medium text-gray-900 mb-2">Specifications:</h4>
                                                <div className="text-sm text-gray-600">
                                                    {Object.entries(JSON.parse(service.specifications)).map(([key, value]) => (
                                                        <div key={key} className="flex justify-between">
                                                            <span className="capitalize">{key}:</span>
                                                            <span>{value}</span>
                                                        </div>
                                                    ))}
                                                </div>
                                            </div>
                                        )}
                                        
                                        <div className="flex gap-2">
                                            <a 
                                                href="/get-quote" 
                                                className="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-center font-medium"
                                            >
                                                Get Quote
                                            </a>
                                            <a 
                                                href={`/products/${service.slug}`} 
                                                className="flex-1 bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors text-center font-medium"
                                            >
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <div className="text-center py-12">
                            <h3 className="text-xl font-semibold text-gray-900 mb-2">No services found</h3>
                            <p className="text-gray-600">Please select a different category or contact us for custom solutions.</p>
                        </div>
                    )}
                </div>
            </section>

            {/* Service Categories Overview */}
            <section className="py-16 bg-gray-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">
                            Our Service Categories
                        </h2>
                        <p className="text-xl text-gray-600 max-w-2xl mx-auto">
                            Comprehensive printing solutions organized for your convenience
                        </p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div className="bg-white rounded-lg shadow-lg p-8 text-center">
                            <div className="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg className="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 className="text-2xl font-semibold text-gray-900 mb-4">Business Essentials</h3>
                            <p className="text-gray-600 text-center mb-4">
                                Core printing services for everyday business needs
                            </p>
                            <ul className="space-y-2 mb-6 text-left">
                                <li className="flex items-center">
                                    <svg className="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                    </svg>
                                    Business Cards
                                </li>
                                <li className="flex items-center">
                                    <svg className="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                    </svg>
                                    Flyers & Brochures
                                </li>
                                <li className="flex items-center">
                                    <svg className="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                    </svg>
                                    Marketing Materials
                                </li>
                            </ul>
                            <div className="text-center">
                                <button 
                                    onClick={() => setSelectedCategory('Business Essentials')}
                                    className="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors"
                                >
                                    View Business Essentials
                                </button>
                            </div>
                        </div>

                        <div className="bg-white rounded-lg shadow-lg p-8 text-center">
                            <div className="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg className="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                                </svg>
                            </div>
                            <h3 className="text-2xl font-semibold text-gray-900 mb-4">Banner Solutions</h3>
                            <p className="text-gray-600 text-center mb-4">
                                Professional banner and display solutions for events and marketing
                            </p>
                            <ul className="space-y-2 mb-6 text-left">
                                <li className="flex items-center">
                                    <svg className="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                    </svg>
                                    Pull-up Banners
                                </li>
                                <li className="flex items-center">
                                    <svg className="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                    </svg>
                                    Teardrop Banners
                                </li>
                                <li className="flex items-center">
                                    <svg className="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                    </svg>
                                    Vinyl & Mesh Banners
                                </li>
                            </ul>
                            <div className="text-center">
                                <button 
                                    onClick={() => setSelectedCategory('Banner Solutions')}
                                    className="inline-block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors"
                                >
                                    View Banner Solutions
                                </button>
                            </div>
                        </div>

                        <div className="bg-white rounded-lg shadow-lg p-8 text-center">
                            <div className="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg className="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <h3 className="text-2xl font-semibold text-gray-900 mb-4">Signage & Display</h3>
                            <p className="text-gray-600 text-center mb-4">
                                Premium signage and display materials for maximum impact
                            </p>
                            <ul className="space-y-2 mb-6 text-left">
                                <li className="flex items-center">
                                    <svg className="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                    </svg>
                                    Media Walls
                                </li>
                                <li className="flex items-center">
                                    <svg className="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                    </svg>
                                    Lightbox Fabric
                                </li>
                                <li className="flex items-center">
                                    <svg className="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                    </svg>
                                    Premium Materials
                                </li>
                            </ul>
                            <div className="text-center">
                                <button 
                                    onClick={() => setSelectedCategory('Signage & Display')}
                                    className="inline-block bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition-colors"
                                >
                                    View Signage & Display
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Special Offers */}
            <section className="py-16 bg-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">
                            Special Offers & Packages
                        </h2>
                        <p className="text-xl text-gray-600 max-w-2xl mx-auto">
                            Save money with our bundled printing solutions
                        </p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div className="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg shadow-lg p-8 border border-blue-200">
                            <div className="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg className="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <h3 className="text-2xl font-semibold text-gray-900 mb-4 text-center">Starter Kit</h3>
                            <p className="text-gray-600 text-center mb-4">
                                Perfect for new businesses getting started
                            </p>
                            <ul className="space-y-2 mb-6">
                                <li className="flex items-center">
                                    <svg className="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                    </svg>
                                    500 Business Cards
                                </li>
                                <li className="flex items-center">
                                    <svg className="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                    </svg>
                                    250 A4 Flyers
                                </li>
                                <li className="flex items-center">
                                    <svg className="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                    </svg>
                                    1 Pull-up Banner
                                </li>
                            </ul>
                            <div className="text-center">
                                <a 
                                    href="/get-quote" 
                                    className="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors"
                                >
                                    Get Starter Kit Quote
                                </a>
                            </div>
                        </div>

                        <div className="bg-gradient-to-br from-green-50 to-green-100 rounded-lg shadow-lg p-8 border border-green-200">
                            <div className="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg className="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 className="text-2xl font-semibold text-gray-900 mb-4 text-center">Express Service</h3>
                            <p className="text-gray-600 text-center mb-4">
                                24-48 hour turnaround for urgent projects
                            </p>
                            <ul className="space-y-2 mb-6">
                                <li className="flex items-center">
                                    <svg className="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                    </svg>
                                    Same-day design review
                                </li>
                                <li className="flex items-center">
                                    <svg className="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                    </svg>
                                    24-48 hour production
                                </li>
                                <li className="flex items-center">
                                    <svg className="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                    </svg>
                                    Priority customer support
                                </li>
                            </ul>
                            <div className="text-center">
                                <a 
                                    href="/get-quote" 
                                    className="inline-block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors"
                                >
                                    Get Express Quote
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* CTA Section */}
            <section className="py-16 bg-blue-600 text-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 className="text-3xl font-bold mb-4">
                        Need Something Custom?
                    </h2>
                    <p className="text-xl mb-8 max-w-2xl mx-auto">
                        Don't see exactly what you need? Contact us for custom printing solutions 
                        tailored to your specific requirements.
                    </p>
                    <div className="flex flex-col sm:flex-row gap-4 justify-center">
                        <a 
                            href="/get-quote" 
                            className="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition-colors inline-block"
                        >
                            Get Custom Quote
                        </a>
                        <a 
                            href="/contact" 
                            className="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white hover:text-blue-600 transition-colors inline-block"
                        >
                            Discuss Requirements
                        </a>
                    </div>
                    <div className="mt-8 text-center">
                        <p className="text-lg">
                            <strong>Call us:</strong> {phone} | <strong>Email:</strong> {email}
                        </p>
                    </div>
                </div>
            </section>
        </Layout>
    );
}
