import Layout from '@/Components/Layout';
import { useForm } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import { HumanVerificationWrapper, HoneypotField, FormTimer } from '@/Components/HumanVerification';

// Error boundary component
const ContactErrorBoundary = ({ children }) => {
    const [hasError, setHasError] = useState(false);
    
    if (hasError) {
        return (
            <div className="min-h-screen flex items-center justify-center bg-gray-50">
                <div className="text-center">
                    <h1 className="text-2xl font-bold text-gray-900 mb-4">Something went wrong</h1>
                    <p className="text-gray-600 mb-4">Please refresh the page to try again.</p>
                    <button 
                        onClick={() => window.location.reload()} 
                        className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                    >
                        Refresh Page
                    </button>
                </div>
            </div>
        );
    }
    
    try {
        return children;
    } catch (error) {
        console.error('Contact component error:', error);
        setHasError(true);
        return null;
    }
};

export default function Contact({ phone, email, hours, csrf_token, success, error, errors, captcha }) {
    const [showSuccess, setShowSuccess] = useState(false);
    const [showError, setShowError] = useState(false);
    const [verificationData, setVerificationData] = useState(null);
    const [formStartTime, setFormStartTime] = useState(null);
    
    const { data, setData, post, processing, reset } = useForm({
        name: '',
        email: '',
        message: '',
        'g-recaptcha-response': '',
        'h-captcha-response': '',
        form_start_time: null
    });

    // Handle success message from props
    useEffect(() => {
        if (success) {
            setShowSuccess(true);
            reset();
            // Hide success message after 5 seconds
            setTimeout(() => {
                setShowSuccess(false);
            }, 5000);
        }
    }, [success, reset]);

    // Handle error message from props
    useEffect(() => {
        if (error) {
            setShowError(true);
            // Hide error message after 5 seconds
            setTimeout(() => {
                setShowError(false);
            }, 5000);
        }
    }, [error]);

    // Set form start time when component mounts
    useEffect(() => {
        const startTime = Math.floor(Date.now() / 1000);
        setFormStartTime(startTime);
        setData('form_start_time', startTime);
    }, []);

    const handleVerificationComplete = (verificationInfo) => {
        setVerificationData(verificationInfo);
        
        // Set the appropriate CAPTCHA response
        if (captcha?.type === 'recaptcha' && verificationInfo.token) {
            setData('g-recaptcha-response', verificationInfo.token);
        } else if (captcha?.type === 'hcaptcha' && verificationInfo.token) {
            setData('h-captcha-response', verificationInfo.token);
        }
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        
        // Ensure verification data is available when CAPTCHA is active
        if (captcha?.type !== 'none' && !verificationData?.token) {
            setShowError(true);
            setTimeout(() => setShowError(false), 5000);
            return;
        }
        
        post('/contact');
    };

    // Handle success message from props
    useEffect(() => {
        if (success) {
            setShowSuccess(true);
            reset();
            // Hide success message after 5 seconds
            setTimeout(() => {
                setShowSuccess(false);
            }, 5000);
        }
    }, [success, reset]);

    // Handle error message from props
    useEffect(() => {
        if (error) {
            setShowError(true);
            // Hide error message after 5 seconds
            setTimeout(() => {
                setShowError(false);
            }, 5000);
        }
    }, [error]);

    return (
        <ContactErrorBoundary>
            <Layout
                title="Contact Melbourne Print Hub"
                csrf_token={csrf_token}
                meta={{
                    description: 'Contact Melbourne Print Hub for quotes, artwork support, and urgent same-day printing in Melbourne. Call 0449 598 440 or visit our CBD location at 58 Leonard Avenue.',
                    keywords: 'contact melbourne print hub, print shop melbourne contact, same day printing melbourne contact',
                    canonical: 'https://melbourneprinthub.com.au/contact',
                    structuredData: [
                        {
                            '@context': 'https://schema.org',
                            '@type': 'ContactPage',
                            name: 'Contact Melbourne Print Hub',
                            description: 'Reach the Melbourne Print Hub team to request printing quotes, confirm artwork, and organise same-day pickup or delivery in Melbourne.',
                            url: 'https://melbourneprinthub.com.au/contact',
                        },
                    ],
                }}
            >
            {/* Hero Section */}
            <section className="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h1 className="text-4xl md:text-6xl font-bold mb-6">
                        Contact Melbourne Print Hub
                    </h1>
                    <p className="text-xl md:text-2xl max-w-3xl mx-auto">
                        Speak directly with local printing specialists for quotes, artwork checks, and delivery timelines.
                    </p>
                </div>
            </section>

            {/* Contact Information & Form */}
            <section className="py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        {/* Contact Information */}
                        <div>
                            <h2 className="text-3xl font-bold text-gray-900 mb-8">
                                Get in Touch
                            </h2>
                            
                            <div className="space-y-8">
                                <div className="flex items-start">
                                    <div className="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                        <svg className="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 className="text-lg font-semibold text-gray-900 mb-2">Phone</h3>
                                        <p className="text-gray-600 mb-2">Speak directly with our printing experts</p>
                                        <a 
                                            href={`tel:${phone}`} 
                                            className="text-blue-600 font-semibold text-lg hover:text-blue-700"
                                        >
                                            {phone}
                                        </a>
                                    </div>
                                </div>

                                <div className="flex items-start">
                                    <div className="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                        <svg className="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 className="text-lg font-semibold text-gray-900 mb-2">Email</h3>
                                        <p className="text-gray-600 mb-2">Send us a detailed message</p>
                                        <a 
                                            href={`mailto:${email}`} 
                                            className="text-green-600 font-semibold text-lg hover:text-green-700"
                                        >
                                            {email}
                                        </a>
                                    </div>
                                </div>

                                <div className="flex items-start">
                                    <div className="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                        <svg className="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 className="text-lg font-semibold text-gray-900 mb-2">Visit Our Print Studio</h3>
                                        <p className="text-gray-600">Melbourne Print Hub</p>
                                        <p className="text-gray-600">58 Leonard Avenue</p>
                                        <p className="text-gray-600">Noble Park VIC 3174</p>
                                        <p className="text-sm text-gray-500 mt-2">Ground floor collection with secure loading zone and delivery options across metropolitan Melbourne.</p>
                                    </div>
                                </div>

                                <div className="flex items-start">
                                    <div className="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                        <svg className="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 className="text-lg font-semibold text-gray-900 mb-2">Opening Hours</h3>
                                        <p className="text-gray-600 mb-2">When we're available to help</p>
                                        <p className="text-gray-900 font-semibold">{hours}</p>
                                    </div>
                                </div>

                                <div className="flex items-start">
                                    <div className="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                        <svg className="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 className="text-lg font-semibold text-gray-900 mb-2">Location</h3>
                                        <p className="text-gray-600 mb-2">Visit our printing facility</p>
                                        <p className="text-gray-900 font-semibold">Melbourne, Victoria</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Contact Form */}
                        <div className="bg-white rounded-lg shadow-lg p-8">
                            <h2 className="text-2xl font-bold text-gray-900 mb-6">
                                Send Us a Message
                            </h2>
                            
                            {/* Success Message */}
                            {showSuccess && (
                                <div className="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                                    <div className="flex items-center">
                                        <svg className="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                        </svg>
                                        <span className="font-medium">{success}</span>
                                    </div>
                                </div>
                            )}
                            
                            {/* Error Message */}
                            {showError && (
                                <div className="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                                    <div className="flex items-center">
                                        <svg className="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clipRule="evenodd" />
                                        </svg>
                                        <span className="font-medium">{error}</span>
                                    </div>
                                </div>
                            )}

                            <form onSubmit={handleSubmit} className="space-y-6">
                                <HumanVerificationWrapper
                                    verificationType={captcha?.type || 'recaptcha'}
                                    recaptchaSiteKey={captcha?.recaptcha_sitekey}
                                    hcaptchaSiteKey={captcha?.hcaptcha_sitekey}
                                    onVerificationComplete={handleVerificationComplete}
                                >
                                    <HoneypotField name="website" />
                                    
                                    <div>
                                        <label htmlFor="name" className="block text-sm font-medium text-gray-700 mb-2">
                                            Full Name *
                                        </label>
                                        <input
                                            type="text"
                                            id="name"
                                            value={data.name}
                                            onChange={e => setData('name', e.target.value)}
                                            className={`w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 ${
                                                errors.name ? 'border-red-500' : 'border-gray-300'
                                            }`}
                                                                                placeholder="Your full name"
                                    required
                                    autoComplete="name"
                                        />
                                        {errors.name && (
                                            <p className="mt-1 text-sm text-red-600">{errors.name}</p>
                                        )}
                                    </div>

                                    <div>
                                        <label htmlFor="email" className="block text-sm font-medium text-gray-700 mb-2">
                                            Email Address *
                                        </label>
                                        <input
                                            type="email"
                                            id="email"
                                            value={data.email}
                                            onChange={e => setData('email', e.target.value)}
                                            className={`w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 ${
                                                errors.email ? 'border-red-500' : 'border-gray-300'
                                            }`}
                                                                                placeholder="your.email@example.com"
                                    required
                                    autoComplete="email"
                                        />
                                        {errors.email && (
                                            <p className="mt-1 text-sm text-red-600">{errors.email}</p>
                                        )}
                                    </div>

                                    <div>
                                        <label htmlFor="message" className="block text-sm font-medium text-gray-700 mb-2">
                                            Message *
                                        </label>
                                        <textarea
                                            id="message"
                                            value={data.message}
                                            onChange={e => setData('message', e.target.value)}
                                            rows={6}
                                            className={`w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 ${
                                                errors.message ? 'border-red-500' : 'border-gray-300'
                                            }`}
                                            placeholder="Tell us about your printing needs, ask questions, or request a consultation..."
                                            required
                                        />
                                        {errors.message && (
                                            <p className="mt-1 text-sm text-red-600">{errors.message}</p>
                                        )}
                                    </div>

                                    <div className="text-center">
                                        <button
                                            type="submit"
                                            disabled={processing || (captcha?.type !== 'none' && !verificationData?.token)}
                                            className="bg-blue-600 text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed w-full"
                                        >
                                            {processing ? 'Sending...' : 'Send Message'}
                                        </button>
                                    </div>
                                </HumanVerificationWrapper>
                            </form>

                            <div className="mt-6 text-center text-sm text-gray-600">
                                <p>We'll get back to you within 24 hours</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* FAQ Section */}
            <section className="py-16 bg-gray-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 className="text-3xl font-bold text-gray-900 text-center mb-12">
                        Frequently Asked Questions
                    </h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div className="bg-white rounded-lg p-6 shadow-lg">
                            <h3 className="text-lg font-semibold text-gray-900 mb-3">
                                What file formats do you accept?
                            </h3>
                            <p className="text-gray-600">
                                We accept PDF, AI, EPS, PSD, JPG, PNG, and TIFF files. 
                                For best results, we recommend high-resolution PDF files.
                            </p>
                        </div>

                        <div className="bg-white rounded-lg p-6 shadow-lg">
                            <h3 className="text-lg font-semibold text-gray-900 mb-3">
                                Do you offer delivery services?
                            </h3>
                            <p className="text-gray-600">
                                Yes, we offer both pickup and delivery options. We can deliver throughout 
                                Melbourne and surrounding areas, or you can collect from our location.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {/* Map Section */}
            <section className="pb-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="bg-white rounded-lg shadow-lg overflow-hidden">
                        <iframe
                            title="Melbourne Print Hub Location"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.8433207008346!2d144.96487347647473!3d-37.81562703734348!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642af0f11fd81%3A0x5045675218ce470!2sMelbourne%20VIC%203000!5e0!3m2!1sen!2sau!4v1737158400000!5m2!1sen!2sau"
                            width="100%"
                            height="360"
                            style={{ border: 0 }}
                            allowFullScreen=""
                            loading="lazy"
                            referrerPolicy="no-referrer-when-downgrade"
                        />
                    </div>
                </div>
            </section>

            {/* CTA Section */}
            <section className="py-16 bg-blue-600 text-white">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 className="text-3xl font-bold mb-4">
                        Ready to Start Your Project?
                    </h2>
                    <p className="text-xl mb-8 max-w-2xl mx-auto">
                        Get in touch today and let's discuss how we can help bring your printing vision to life.
                    </p>
                    <div className="flex flex-col sm:flex-row gap-4 justify-center">
                        <a 
                            href="/get-quote" 
                            className="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition-colors inline-block"
                        >
                            Get a Quote
                        </a>
                        <a 
                            href={`tel:${phone}`} 
                            className="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white hover:text-blue-600 transition-colors inline-block"
                        >
                            Call Now
                        </a>
                    </div>
                    <div className="mt-8 text-center">
                        <p className="text-lg">
                            <strong>Phone:</strong> {phone} | <strong>Email:</strong> {email}
                        </p>
                        <p className="text-lg mt-2">
                            <strong>Opening Hours:</strong> {hours}
                        </p>
                    </div>
                </div>
            </section>
        </Layout>
    </ContactErrorBoundary>
    );
}
