import Layout from '@/Components/Layout';

export default function Home({ services, phone, email, csrf_token }) {
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
            {/* Hero Section */}
            <section className="relative bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h1 className="text-4xl md:text-6xl font-bold mb-6">
                        Printing Services in Melbourne That Move as Fast as Your Business
                    </h1>
                    <p className="text-xl md:text-2xl mb-8 max-w-3xl mx-auto">
                        Melbourne Print Hub helps local businesses create professional marketing material—business cards, flyers, brochures, and banners—with same-day turnaround and expert design advice.
                    </p>
                    <div className="flex flex-col sm:flex-row gap-4 justify-center">
                        <a 
                            href="/get-quote" 
                            className="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition-colors inline-block"
                        >
                            Get Your Quote Now
                        </a>
                        <a 
                            href="/services" 
                            className="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white hover:text-blue-600 transition-colors inline-block"
                        >
                            View Our Services
                        </a>
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
