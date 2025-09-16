import Layout from '@/Components/Layout';

export default function About({ phone, email, hours, csrf_token }) {
    return (
        <Layout title="About Us" csrf_token={csrf_token}>
            {/* Hero Section */}
            <section className="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h1 className="text-4xl md:text-6xl font-bold mb-6">
                        About Melbourne Print Hub
                    </h1>
                    <p className="text-xl md:text-2xl max-w-3xl mx-auto">
                        Your trusted partner for professional printing solutions in Melbourne
                    </p>
                </div>
            </section>

            {/* Main Content */}
            <section className="py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                        <div>
                            <h2 className="text-3xl font-bold text-gray-900 mb-6">
                                Our Story
                            </h2>
                            <p className="text-lg text-gray-600 mb-6">
                                Melbourne Print Hub was founded with a simple mission: to provide Melbourne businesses 
                                with fast, reliable, and affordable printing services without compromising on quality.
                            </p>
                            <p className="text-lg text-gray-600 mb-6">
                                We understand that in today's fast-paced business environment, you need printing 
                                solutions that are not only professional but also delivered on time. That's why we've 
                                built our business around speed, quality, and exceptional customer service.
                            </p>
                            <p className="text-lg text-gray-600">
                                From small business cards to large format banners, we handle every project with 
                                the same attention to detail and commitment to excellence.
                            </p>
                        </div>
                        <div className="bg-gray-100 rounded-lg p-8">
                            <h3 className="text-2xl font-semibold text-gray-900 mb-4">Quick Facts</h3>
                            <div className="space-y-4">
                                <div className="flex items-center">
                                    <div className="w-3 h-3 bg-blue-600 rounded-full mr-3"></div>
                                    <span className="text-gray-700">Melbourne-based business</span>
                                </div>
                                <div className="flex items-center">
                                    <div className="w-3 h-3 bg-blue-600 rounded-full mr-3"></div>
                                    <span className="text-gray-700">Fast 24-48 hour turnaround</span>
                                </div>
                                <div className="flex items-center">
                                    <div className="w-3 h-3 bg-blue-600 rounded-full mr-3"></div>
                                    <span className="text-gray-700">Professional design support</span>
                                </div>
                                <div className="flex items-center">
                                    <div className="w-3 h-3 bg-blue-600 rounded-full mr-3"></div>
                                    <span className="text-gray-700">Competitive pricing</span>
                                </div>
                                <div className="flex items-center">
                                    <div className="w-3 h-3 bg-blue-600 rounded-full mr-3"></div>
                                    <span className="text-gray-700">Local customer service</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Mission & Vision */}
            <section className="py-16 bg-gray-100">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <div className="bg-white rounded-lg p-8 shadow-lg">
                            <div className="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg className="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                            </div>
                            <h3 className="text-2xl font-semibold text-gray-900 mb-4 text-center">Our Mission</h3>
                            <p className="text-gray-600 text-center">
                                To provide Melbourne businesses with professional printing solutions that help them 
                                grow and succeed, delivered with speed, quality, and exceptional customer service.
                            </p>
                        </div>

                        <div className="bg-white rounded-lg p-8 shadow-lg">
                            <div className="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg className="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                            <h3 className="text-2xl font-semibold text-gray-900 mb-4 text-center">Our Vision</h3>
                            <p className="text-gray-600 text-center">
                                To become Melbourne's most trusted printing partner, known for reliability, 
                                quality, and exceptional customer experience.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {/* Values */}
            <section className="py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">
                            Our Core Values
                        </h2>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div className="text-center">
                            <div className="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg className="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <h3 className="text-xl font-semibold text-gray-900 mb-2">Speed</h3>
                            <p className="text-gray-600">
                                We understand time is money. Fast turnaround times without compromising quality.
                            </p>
                        </div>

                        <div className="text-center">
                            <div className="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg className="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 className="text-xl font-semibold text-gray-900 mb-2">Quality</h3>
                            <p className="text-gray-600">
                                Every project receives our full attention to ensure the highest quality results.
                            </p>
                        </div>

                        <div className="text-center">
                            <div className="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg className="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h3 className="text-xl font-semibold text-gray-900 mb-2">Service</h3>
                            <p className="text-gray-600">
                                Exceptional customer service is at the heart of everything we do.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {/* CTA Section */}
            <section className="py-16 bg-blue-600 text-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 className="text-3xl font-bold mb-4">
                        Ready to Work With Us?
                    </h2>
                    <p className="text-xl mb-8 max-w-2xl mx-auto">
                        Let's discuss your printing needs and how we can help bring your vision to life.
                    </p>
                    <div className="flex flex-col sm:flex-row gap-4 justify-center">
                        <a 
                            href="/get-quote" 
                            className="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition-colors inline-block"
                        >
                            Get a Quote
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
                        <p className="text-lg mt-2">
                            <strong>Opening Hours:</strong> {hours}
                        </p>
                    </div>
                </div>
            </section>
        </Layout>
    );
}
