import Layout from '@/Components/Layout';
import { useState } from 'react';

export default function EmailTesting({ emailConfig, testResults }) {
    const [formData, setFormData] = useState({
        name: '',
        email: '',
        message: ''
    });
    const [processing, setProcessing] = useState(false);
    const [result, setResult] = useState(null);
    const [errors, setErrors] = useState({});

    const handleSubmit = async (e) => {
        e.preventDefault();
        setProcessing(true);
        setErrors({});
        setResult(null);

        try {
            const response = await fetch('/email-testing/test', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (response.ok) {
                setResult(data);
            } else {
                if (data.errors) {
                    setErrors(data.errors);
                } else {
                    setResult({ success: false, message: data.message || 'An error occurred' });
                }
            }
        } catch (error) {
            setResult({ success: false, message: 'Network error: ' + error.message });
        } finally {
            setProcessing(false);
        }
    };

    const handleInputChange = (field, value) => {
        setFormData(prev => ({ ...prev, [field]: value }));
        if (errors[field]) {
            setErrors(prev => ({ ...prev, [field]: null }));
        }
    };

    const getConfigStatusColor = (status) => {
        if (status === 'Configured' || status === 'smtp') return 'text-green-600';
        if (status === 'Not configured' || status === 'log') return 'text-red-600';
        return 'text-yellow-600';
    };

    return (
        <Layout title="Email Testing">
            {/* Hero Section */}
            <section className="bg-gradient-to-r from-green-600 to-green-800 text-white py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h1 className="text-4xl md:text-5xl font-bold mb-6">
                        Email Testing Dashboard
                    </h1>
                    <p className="text-xl md:text-2xl max-w-3xl mx-auto">
                        Test email functionality on desktop and mobile platforms
                    </p>
                </div>
            </section>

            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    {/* Email Configuration Status */}
                    <div className="bg-white rounded-lg shadow-lg p-8">
                        <h2 className="text-2xl font-bold text-gray-900 mb-6">
                            Email Configuration Status
                        </h2>
                        
                        <div className="space-y-4">
                            <div className="flex justify-between items-center py-2 border-b">
                                <span className="font-medium">Mail Driver:</span>
                                <span className={getConfigStatusColor(emailConfig.mail_driver)}>
                                    {emailConfig.mail_driver}
                                </span>
                            </div>
                            
                            <div className="flex justify-between items-center py-2 border-b">
                                <span className="font-medium">From Address:</span>
                                <span className={getConfigStatusColor(emailConfig.from_address)}>
                                    {emailConfig.from_address}
                                </span>
                            </div>
                            
                            <div className="flex justify-between items-center py-2 border-b">
                                <span className="font-medium">From Name:</span>
                                <span className="text-gray-600">{emailConfig.from_name}</span>
                            </div>
                            
                            <div className="flex justify-between items-center py-2 border-b">
                                <span className="font-medium">SMTP Host:</span>
                                <span className="text-gray-600">{emailConfig.smtp_host}</span>
                            </div>
                            
                            <div className="flex justify-between items-center py-2 border-b">
                                <span className="font-medium">SMTP Port:</span>
                                <span className="text-gray-600">{emailConfig.smtp_port}</span>
                            </div>
                            
                            <div className="flex justify-between items-center py-2 border-b">
                                <span className="font-medium">SMTP Username:</span>
                                <span className={getConfigStatusColor(emailConfig.smtp_username)}>
                                    {emailConfig.smtp_username}
                                </span>
                            </div>
                            
                            <div className="flex justify-between items-center py-2 border-b">
                                <span className="font-medium">SMTP Password:</span>
                                <span className={getConfigStatusColor(emailConfig.smtp_password)}>
                                    {emailConfig.smtp_password}
                                </span>
                            </div>
                            
                            <div className="flex justify-between items-center py-2 border-b">
                                <span className="font-medium">App Environment:</span>
                                <span className="text-gray-600">{emailConfig.app_env}</span>
                            </div>
                        </div>

                        <div className="mt-6 p-4 bg-gray-50 rounded-lg">
                            <h3 className="font-semibold text-gray-900 mb-2">Testing Instructions:</h3>
                            <ul className="text-sm text-gray-600 space-y-1">
                                <li>• Fill out the form on the right</li>
                                <li>• Submit to test email functionality</li>
                                <li>• Check your email for the test message</li>
                                <li>• Review the detailed results below</li>
                            </ul>
                        </div>
                    </div>

                    {/* Email Test Form */}
                    <div className="bg-white rounded-lg shadow-lg p-8">
                        <h2 className="text-2xl font-bold text-gray-900 mb-6">
                            Email Test Form
                        </h2>
                        
                        <form onSubmit={handleSubmit} className="space-y-6">
                            <div>
                                <label htmlFor="name" className="block text-sm font-medium text-gray-700 mb-2">
                                    Full Name *
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    value={formData.name}
                                    onChange={(e) => handleInputChange('name', e.target.value)}
                                    className={`w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 ${
                                        errors.name ? 'border-red-500' : 'border-gray-300'
                                    }`}
                                    placeholder="Your full name"
                                    required
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
                                    value={formData.email}
                                    onChange={(e) => handleInputChange('email', e.target.value)}
                                    className={`w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 ${
                                        errors.email ? 'border-red-500' : 'border-gray-300'
                                    }`}
                                    placeholder="your.email@example.com"
                                    required
                                />
                                {errors.email && (
                                    <p className="mt-1 text-sm text-red-600">{errors.email}</p>
                                )}
                            </div>

                            <div>
                                <label htmlFor="message" className="block text-sm font-medium text-gray-700 mb-2">
                                    Test Message *
                                </label>
                                <textarea
                                    id="message"
                                    value={formData.message}
                                    onChange={(e) => handleInputChange('message', e.target.value)}
                                    rows={4}
                                    className={`w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 ${
                                        errors.message ? 'border-red-500' : 'border-gray-300'
                                    }`}
                                    placeholder="Enter your test message here..."
                                    required
                                />
                                {errors.message && (
                                    <p className="mt-1 text-sm text-red-600">{errors.message}</p>
                                )}
                            </div>

                            <div className="text-center">
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="bg-green-600 text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed w-full"
                                >
                                    {processing ? 'Testing Email...' : 'Test Email Sending'}
                                </button>
                            </div>
                        </form>

                        {/* Mobile Test Link */}
                        <div className="mt-6 text-center">
                            <a 
                                href="/email-testing/mobile-test" 
                                className="text-blue-600 hover:text-blue-700 font-medium"
                            >
                                📱 Test on Mobile Device
                            </a>
                        </div>
                    </div>
                </div>

                {/* Test Results */}
                {result && (
                    <div className="mt-12 bg-white rounded-lg shadow-lg p-8">
                        <h2 className="text-2xl font-bold text-gray-900 mb-6">
                            Test Results
                        </h2>
                        
                        <div className={`p-6 rounded-lg ${
                            result.success ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'
                        }`}>
                            <div className="flex items-center mb-4">
                                {result.success ? (
                                    <svg className="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                                    </svg>
                                ) : (
                                    <svg className="w-6 h-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                )}
                                <h3 className={`text-lg font-semibold ${
                                    result.success ? 'text-green-800' : 'text-red-800'
                                }`}>
                                    {result.success ? 'Test Successful' : 'Test Failed'}
                                </h3>
                            </div>
                            
                            <p className={`mb-4 ${
                                result.success ? 'text-green-700' : 'text-red-700'
                            }`}>
                                {result.message}
                            </p>

                            {result.details && (
                                <div className="space-y-3">
                                    <h4 className="font-semibold text-gray-900">Test Details:</h4>
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span className="font-medium">Platform:</span> {result.details.platform}
                                        </div>
                                        <div>
                                            <span className="font-medium">IP Address:</span> {result.details.ip}
                                        </div>
                                        <div>
                                            <span className="font-medium">Contact Message ID:</span> {result.details.contact_message_id}
                                        </div>
                                        <div>
                                            <span className="font-medium">Email Sent:</span> {result.details.email_sent ? 'Yes' : 'No'}
                                        </div>
                                    </div>
                                    
                                    {result.details.user_agent && (
                                        <div className="mt-3">
                                            <span className="font-medium">User Agent:</span>
                                            <p className="text-xs text-gray-600 mt-1 break-all">{result.details.user_agent}</p>
                                        </div>
                                    )}
                                </div>
                            )}

                            {result.details?.email_content && (
                                <div className="mt-4">
                                    <h4 className="font-semibold text-gray-900 mb-2">Email Content:</h4>
                                    <pre className="bg-gray-100 p-4 rounded text-xs overflow-x-auto">
                                        {result.details.email_content}
                                    </pre>
                                </div>
                            )}

                            {result.details?.error && (
                                <div className="mt-4">
                                    <h4 className="font-semibold text-red-800 mb-2">Error Details:</h4>
                                    <pre className="bg-red-50 p-4 rounded text-xs text-red-700 overflow-x-auto">
                                        {result.details.error}
                                    </pre>
                                </div>
                            )}
                        </div>
                    </div>
                )}
            </div>
        </Layout>
    );
}
