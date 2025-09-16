import Layout from '@/Components/Layout';
import { useState, useEffect } from 'react';
import { useForm } from '@inertiajs/react';
import { HumanVerificationWrapper, HoneypotField, FormTimer } from '@/Components/HumanVerification';

const STATE_ABBREVIATIONS = ['ACT', 'NSW', 'NT', 'QLD', 'SA', 'TAS', 'VIC', 'WA'];

export default function Quote({ services, phone, email, csrf_token, success, error, errors: propErrors, captcha }) {
    const [showSuccess, setShowSuccess] = useState(false);
    const [showError, setShowError] = useState(false);
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [validationErrors, setValidationErrors] = useState({});
    const [verificationData, setVerificationData] = useState(null);
    const [formStartTime, setFormStartTime] = useState(null);
    
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        phone: '',
        service: '',
        serviceCategory: '',
        description: '',
        quantity: '',
        size: '',
        artwork: null,
        addressStreet: '',
        addressSuburb: '',
        addressState: '',
        addressPostcode: '',
        specialRequirements: '',
        'g-recaptcha-response': '',
        'h-captcha-response': '',
        form_start_time: null
    });

    // Progressive validation functions
    const validateField = (field, value) => {
        const newErrors = { ...validationErrors };
        
        switch (field) {
            case 'name':
                if (!value.trim()) {
                    newErrors.name = 'Name is required';
                } else if (value.length < 2) {
                    newErrors.name = 'Name must be at least 2 characters';
                } else {
                    delete newErrors.name;
                }
                break;
                
            case 'email':
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!value.trim()) {
                    newErrors.email = 'Email is required';
                } else if (!emailRegex.test(value)) {
                    newErrors.email = 'Please enter a valid email address';
                } else {
                    delete newErrors.email;
                }
                break;
                
            case 'phone':
                if (!value.trim()) {
                    newErrors.phone = 'Phone number is required';
                } else if (value.length < 8) {
                    newErrors.phone = 'Phone number must be at least 8 digits';
                } else {
                    delete newErrors.phone;
                }
                break;
                
            case 'service':
                if (!value.trim()) {
                    newErrors.service = 'Please select a service';
                } else {
                    delete newErrors.service;
                }
                break;
                
            case 'description':
                if (!value.trim()) {
                    newErrors.description = 'Description is required';
                } else if (value.length < 10) {
                    newErrors.description = 'Description must be at least 10 characters';
                } else {
                    delete newErrors.description;
                }
                break;
                
            case 'quantity':
                if (!value.trim()) {
                    newErrors.quantity = 'Quantity is required';
                } else {
                    delete newErrors.quantity;
                }
                break;

            case 'addressStreet':
                if (!value.trim()) {
                    newErrors.addressStreet = 'Street address is required';
                } else {
                    delete newErrors.addressStreet;
                }
                break;

            case 'addressSuburb':
                if (!value.trim()) {
                    newErrors.addressSuburb = 'Suburb or town is required';
                } else {
                    delete newErrors.addressSuburb;
                }
                break;

            case 'addressState':
                const allowedStates = STATE_ABBREVIATIONS;
                if (!value.trim()) {
                    newErrors.addressState = 'State or territory is required';
                } else if (!allowedStates.includes(value.trim().toUpperCase())) {
                    newErrors.addressState = 'Use a valid state or territory abbreviation';
                } else {
                    delete newErrors.addressState;
                }
                break;

            case 'addressPostcode':
                const postcode = value.trim();
                if (!postcode) {
                    delete newErrors.addressPostcode;
                } else if (!/^\d{4}$/.test(postcode)) {
                    newErrors.addressPostcode = 'Postcode must be 4 digits';
                } else {
                    delete newErrors.addressPostcode;
                }
                break;
        }
        
        setValidationErrors(newErrors);
        return Object.keys(newErrors).length === 0;
    };

    // Handle field changes with progressive validation
    const handleFieldChange = (field, value) => {
        let nextValue = value;

        if (field === 'addressSuburb') {
            nextValue = value.toUpperCase();
        } else if (field === 'addressState') {
            nextValue = value.toUpperCase().replace(/[^A-Z]/g, '').slice(0, 3);
        } else if (field === 'addressPostcode') {
            nextValue = value.replace(/[^0-9]/g, '').slice(0, 4);
        }

        setData(field, nextValue);
        
        // Validate field after user stops typing (debounced)
        setTimeout(() => {
            validateField(field, nextValue);
        }, 500);
    };

    // Handle success message from props
    useEffect(() => {
        if (success) {
            setShowSuccess(true);
            reset();
            setValidationErrors({});
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
        
        // Validate all fields before submission
        const fieldsToValidate = ['name', 'email', 'phone', 'service', 'description', 'quantity', 'addressStreet', 'addressSuburb', 'addressState', 'addressPostcode'];
        let isValid = true;
        
        fieldsToValidate.forEach(field => {
            if (!validateField(field, data[field])) {
                isValid = false;
            }
        });
        
        if (!isValid) {
            return;
        }
        
        // Ensure verification data is available
        if (captcha?.type !== 'none' && !verificationData?.token) {
            setShowError(true);
            setTimeout(() => setShowError(false), 5000);
            return;
        }
        
        // Clear any previous success/error messages
        setShowSuccess(false);
        setShowError(false);
        
        // Show immediate optimistic feedback
        setIsSubmitting(true);
        
        post('/get-quote', {
            onFinish: () => {
                setIsSubmitting(false);
            }
        });
    };

    const handleFileChange = (e) => {
        const file = e.target.files[0];
        if (file) {
            setData('artwork', file);
        }
    };

    const allServices = Object.values(services).flat();
    const serviceCategories = Object.keys(services);

    // Filter services by category when category is selected
    const filteredServices = data.serviceCategory 
        ? (services[data.serviceCategory] || [])
        : allServices;

    // Check if form is valid for submission
    const isFormValid = () => {
        return data.name && data.email && data.phone && data.service && data.description && data.quantity &&
               data.addressStreet && data.addressSuburb && data.addressState &&
               !validationErrors.name && !validationErrors.email && !validationErrors.phone &&
               !validationErrors.service && !validationErrors.description && !validationErrors.quantity &&
               !validationErrors.addressStreet && !validationErrors.addressSuburb &&
               !validationErrors.addressState && !validationErrors.addressPostcode;
    };

    const requiresCaptcha = captcha?.type && captcha?.type !== 'none';
    const isSubmitDisabled = processing || isSubmitting || !isFormValid() || (requiresCaptcha && !verificationData?.token);

    const submitButtonClasses = [
        'inline-flex items-center justify-center bg-blue-600 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-300',
        isSubmitDisabled
            ? 'opacity-50 cursor-not-allowed'
            : 'hover:bg-blue-700 hover:-translate-y-0.5 hover:shadow-lg shadow-blue-500/20 cursor-pointer'
    ].join(' ');

    return (
                    <Layout title="Get a Quote" csrf_token={csrf_token}>
            {/* Hero Section */}
            <section className="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h1 className="text-4xl md:text-6xl font-bold mb-6">
                        Get Your Quote
                    </h1>
                    <p className="text-xl md:text-2xl max-w-3xl mx-auto">
                        Fast, quotes for all your printing needs
                    </p>
                </div>
            </section>

            {/* Quote Form Section */}
            <section className="py-16">
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="bg-white rounded-lg shadow-lg p-8">
                        <div className="text-center mb-8">
                            <h2 className="text-3xl font-bold text-gray-900 mb-4">
                                Request Your Quote
                            </h2>
                            <p className="text-lg text-gray-600">
                                Fill out the form below and we'll get back to you soon.
                            </p>
                        </div>

                        {/* Optimistic Success Message */}
                        {showSuccess && (
                            <div className="bg-green-50 border border-green-200 rounded-lg p-4 mb-6 animate-fade-in">
                                <div className="flex items-center">
                                    <svg className="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span className="text-green-800 font-medium">{success}</span>
                                </div>
                            </div>
                        )}

                        {/* Error Message */}
                        {showError && (
                            <div className="bg-red-50 border border-red-200 rounded-lg p-4 mb-6 animate-fade-in">
                                <div className="flex items-center">
                                    <svg className="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    <span className="text-red-800 font-medium">{error}</span>
                                </div>
                            </div>
                        )}

                        <form onSubmit={handleSubmit} className="space-y-6" autoComplete="on">
                            <HumanVerificationWrapper
                                verificationType={captcha?.type || 'recaptcha'}
                                recaptchaSiteKey={captcha?.recaptcha_sitekey}
                                hcaptchaSiteKey={captcha?.hcaptcha_sitekey}
                                onVerificationComplete={handleVerificationComplete}
                            >
                                <HoneypotField name="website" />
                                
                                {/* Name Field with Progressive Validation */}
                                <div>
                                    <label htmlFor="name" className="block text-sm font-medium text-gray-700 mb-2">
                                        Name *
                                    </label>
                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        value={data.name}
                                        onChange={(e) => handleFieldChange('name', e.target.value)}
                                        className={`w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors ${
                                            validationErrors.name ? 'border-red-500 bg-red-50' : 
                                            data.name ? 'border-green-500 bg-green-50' : 'border-gray-300'
                                        }`}
                                        // placeholder="Your full name"
                                        autoComplete="name"
                                    />
                                    {validationErrors.name && (
                                        <p className="mt-1 text-sm text-red-600 flex items-center">
                                            <svg className="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clipRule="evenodd" />
                                            </svg>
                                            {validationErrors.name}
                                        </p>
                                    )}
                                    {data.name && !validationErrors.name && (
                                        <p className="mt-1 text-sm text-green-600 flex items-center">
                                            <svg className="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                            </svg>
                                            
                                        </p>
                                    )}
                                </div>

                            {/* Email Field with Progressive Validation */}
                            <div>
                                <label htmlFor="email" className="block text-sm font-medium text-gray-700 mb-2">
                                    Email *
                                </label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value={data.email}
                                    onChange={(e) => handleFieldChange('email', e.target.value)}
                                    className={`w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors ${
                                        validationErrors.email ? 'border-red-500 bg-red-50' : 
                                        data.email && !validationErrors.email ? 'border-green-500 bg-green-50' : 'border-gray-300'
                                    }`}
                                    // placeholder="your.email@example.com"
                                    autoComplete="email"
                                />
                                {validationErrors.email && (
                                    <p className="mt-1 text-sm text-red-600 flex items-center">
                                        <svg className="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clipRule="evenodd" />
                                        </svg>
                                        {validationErrors.email}
                                    </p>
                                )}
                                {data.email && !validationErrors.email && (
                                    <p className="mt-1 text-sm text-green-600 flex items-center">
                                        <svg className="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                        </svg>
                                        
                                    </p>
                                )}
                            </div>

                            {/* Phone Number Field with Progressive Validation */}
                            <div>
                                <label htmlFor="phone" className="block text-sm font-medium text-gray-700 mb-2">
                                    Phone Number *
                                </label>
                                <input
                                    type="tel"
                                    id="phone"
                                    name="phone"
                                    value={data.phone}
                                    onChange={(e) => handleFieldChange('phone', e.target.value)}
                                    className={`w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors ${
                                        validationErrors.phone ? 'border-red-500 bg-red-50' : 
                                        data.phone && !validationErrors.phone ? 'border-green-500 bg-green-50' : 'border-gray-300'
                                    }`}
                                    // placeholder="Your phone number"
                                    autoComplete="tel"
                                />
                                {validationErrors.phone && (
                                    <p className="mt-1 text-sm text-red-600 flex items-center">
                                        <svg className="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clipRule="evenodd" />
                                        </svg>
                                        {validationErrors.phone}
                                    </p>
                                )}
                                {data.phone && !validationErrors.phone && (
                                    <p className="mt-1 text-sm text-green-600 flex items-center">
                                        {/* <svg className="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                        </svg> */}
                                        
                                    </p>
                                )}
                            </div>

                            {/* Service Selection */}
                            <div>
                                <label htmlFor="serviceCategory" className="block text-sm font-medium text-gray-700 mb-2">
                                    Service Category
                                </label>
                                <select
                                    id="serviceCategory"
                                    name="serviceCategory"
                                    value={data.serviceCategory}
                                    autoComplete="on"
                                    onChange={e => {
                                        setData('serviceCategory', e.target.value);
                                        setData('service', ''); // Reset service when category changes
                                    }}
                                    className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                >
                                    <option value="">All Categories</option>
                                    {serviceCategories.map((category) => (
                                        <option key={category} value={category}>
                                            {category}
                                        </option>
                                    ))}
                                </select>
                                {validationErrors.service && (
                                    <p className="mt-1 text-sm text-red-600">{validationErrors.service}</p>
                                )}
                            </div>

                            {/* Service Required with Progressive Validation */}
                            <div>
                                <label htmlFor="service" className="block text-sm font-medium text-gray-700 mb-2">
                                    Service Required *
                                </label>
                                <select
                                    id="service"
                                    name="service"
                                    value={data.service}
                                    autoComplete="on"
                                    onChange={e => setData('service', e.target.value)}
                                    className={`w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors ${
                                        validationErrors.service ? 'border-red-500 bg-red-50' : 
                                        data.service ? 'border-green-500 bg-green-50' : 'border-gray-300'
                                    }`}
                                    required
                                >
                                    <option value="">Select a service</option>
                                    {filteredServices.map((service) => (
                                        <option key={service.id} value={service.name}>
                                            {service.name} - From ${service.base_price}
                                        </option>
                                    ))}
                                    <option value="Custom Project">Custom Project</option>
                                </select>
                                {validationErrors.service && (
                                    <p className="mt-1 text-sm text-red-600">{validationErrors.service}</p>
                                )}
                                {data.service && !validationErrors.service && (
                                    <p className="mt-1 text-sm text-green-600">
                                        {/* Looks good!*/}
                                    </p> 
                                )}
                            </div>

                            {/* Project Details */}
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {/* Quantity Field with Progressive Validation */}
                                <div>
                                    <label htmlFor="quantity" className="block text-sm font-medium text-gray-700 mb-2">
                                        Quantity *
                                    </label>
                                    <input
                                        type="text"
                                        id="quantity"
                                        name="quantity"
                                        value={data.quantity}
                                        onChange={(e) => handleFieldChange('quantity', e.target.value)}
                                        className={`w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors ${
                                            validationErrors.quantity ? 'border-red-500 bg-red-50' : 
                                            data.quantity ? 'border-green-500 bg-green-50' : 'border-gray-300'
                                        }`}
                                        placeholder="e.g., 500 business cards"
                                        required
                                        autoComplete="on"
                                    />
                                    {validationErrors.quantity && (
                                        <p className="mt-1 text-sm text-red-600 flex items-center">
                                            <svg className="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clipRule="evenodd" />
                                            </svg>
                                            {validationErrors.quantity}
                                        </p>
                                    )}
                                    {data.quantity && !validationErrors.quantity && (
                                        <p className="mt-1 text-sm text-green-600">
                                            {/* Looks good! */}
                                        </p>
                                    )}
                                </div>

                                {/* Size/Dimensions Field */}
                                <div>
                                    <label htmlFor="size" className="block text-sm font-medium text-gray-700 mb-2">
                                        Size/Dimensions
                                    </label>
                                    <input
                                        type="text"
                                        id="size"
                                        name="size"
                                        value={data.size}
                                        onChange={e => setData('size', e.target.value)}
                                        className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="e.g., A4, 850mm x 2000mm"
                                        autoComplete="on"
                                    />
                                    {errors.size && (
                                        <p className="mt-1 text-sm text-red-600">{errors.size}</p>
                                    )}
                                </div>
                            </div>

                            {/* Artwork Upload */}
                            <div>
                                <label htmlFor="artwork" className="block text-sm font-medium text-gray-700 mb-2">
                                    Artwork/Design Files
                                </label>
                                <div className="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors">
                                    <input
                                        type="file"
                                        id="artwork"
                                        name="artwork"
                                        onChange={handleFileChange}
                                        accept=".pdf,.ai,.eps,.psd,.jpg,.jpeg,.png,.tiff"
                                        className="hidden"
                                    />
                                    <label htmlFor="artwork" className="cursor-pointer">
                                        <svg className="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" strokeWidth={2} strokeLinecap="round" strokeLinejoin="round" />
                                        </svg>
                                        <p className="mt-2 text-sm text-gray-600">
                                            <span className="font-medium text-blue-600 hover:text-blue-500">
                                                {/* To make changes at the Upload File Section. */}
                                                Click to upload
                                            </span> or drag and drop
                                        </p>
                                        <p className="mt-1 text-xs text-gray-500">
                                            PDF, AI, EPS, PSD, JPG, PNG, TIFF up to 50MB
                                        </p>
                                    </label>
                                </div>
                                {data.artwork && (
                                    <p className="mt-2 text-sm text-green-600">
                                        ✓ File selected: {data.artwork.name}
                                    </p>
                                )}
                            </div>

                            {/* Project Description with Progressive Validation */}
                            <div>
                                <label htmlFor="description" className="block text-sm font-medium text-gray-700 mb-2">
                                    Project Description & Requirements *
                                </label>
                                <textarea
                                    id="description"
                                    name="description"
                                    value={data.description}
                                    onChange={(e) => handleFieldChange('description', e.target.value)}
                                    rows={6}
                                    autoComplete="on"
                                    className={`w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors ${
                                        validationErrors.description ? 'border-red-500 bg-red-50' : 
                                        data.description ? 'border-green-500 bg-green-50' : 'border-gray-300'
                                    }`}
                                    placeholder="Describe your project, any specific requirements, design preferences, materials, finishes, or questions you have..."
                                    required
                                />
                                {validationErrors.description && (
                                    <p className="mt-1 text-sm text-red-600 flex items-center">
                                        <svg className="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clipRule="evenodd" />
                                        </svg>
                                        {validationErrors.description}
                                    </p>
                                )}
                                {data.description && !validationErrors.description && (
                                    <p className="mt-1 text-sm text-green-600">
                                        {/* Looks good! */}
                                    </p>
                                )}
                            </div>

                            {/* Delivery Address */}
                            <div>
                                <h3 className="text-lg font-semibold text-gray-900 mb-4">Delivery Address</h3>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div className="md:col-span-2">
                                        <label htmlFor="addressStreet" className="block text-sm font-medium text-gray-700 mb-2">
                                            Street Address *
                                        </label>
                                        <input
                                            type="text"
                                            id="addressStreet"
                                            name="addressStreet"
                                            value={data.addressStreet}
                                            onChange={(e) => handleFieldChange('addressStreet', e.target.value)}
                                            className={`w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors ${
                                                validationErrors.addressStreet ? 'border-red-500 bg-red-50' :
                                                data.addressStreet ? 'border-green-500 bg-green-50' : 'border-gray-300'
                                            }`}
                                            autoComplete="address-line1"
                                            placeholder="Street number and name (e.g., 2/14 Smith St)"
                                            required
                                        />
                                        {validationErrors.addressStreet && (
                                            <p className="mt-1 text-sm text-red-600">{validationErrors.addressStreet}</p>
                                        )}
                                        {errors.addressStreet && (
                                            <p className="mt-1 text-sm text-red-600">{errors.addressStreet}</p>
                                        )}
                                    </div>

                                    <div>
                                        <label htmlFor="addressSuburb" className="block text-sm font-medium text-gray-700 mb-2">
                                            Suburb / Town *
                                        </label>
                                        <input
                                            type="text"
                                            id="addressSuburb"
                                            name="addressSuburb"
                                            value={data.addressSuburb}
                                            onChange={(e) => handleFieldChange('addressSuburb', e.target.value)}
                                            className={`w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors ${
                                                validationErrors.addressSuburb ? 'border-red-500 bg-red-50' :
                                                data.addressSuburb ? 'border-green-500 bg-green-50' : 'border-gray-300'
                                            }`}
                                            autoComplete="address-level2"
                                            placeholder="Suburb or town (uppercase)"
                                            required
                                        />
                                        {validationErrors.addressSuburb && (
                                            <p className="mt-1 text-sm text-red-600">{validationErrors.addressSuburb}</p>
                                        )}
                                        {errors.addressSuburb && (
                                            <p className="mt-1 text-sm text-red-600">{errors.addressSuburb}</p>
                                        )}
                                    </div>

                                    <div>
                                        <label htmlFor="addressState" className="block text-sm font-medium text-gray-700 mb-2">
                                            State / Territory *
                                        </label>
                                        <select
                                            id="addressState"
                                            name="addressState"
                                            value={data.addressState}
                                            autoComplete="address-level1"
                                            onChange={(e) => handleFieldChange('addressState', e.target.value)}
                                            className={`w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors ${
                                                validationErrors.addressState ? 'border-red-500 bg-red-50' :
                                                data.addressState ? 'border-green-500 bg-green-50' : 'border-gray-300'
                                            }`}
                                            required
                                        >
                                            <option value="">Select state or territory</option>
                                            {STATE_ABBREVIATIONS.map((state) => (
                                                <option key={state} value={state}>
                                                    {state}
                                                </option>
                                            ))}
                                        </select>
                                        {validationErrors.addressState && (
                                            <p className="mt-1 text-sm text-red-600">{validationErrors.addressState}</p>
                                        )}
                                        {errors.addressState && (
                                            <p className="mt-1 text-sm text-red-600">{errors.addressState}</p>
                                        )}
                                    </div>

                                    <div>
                                        <label htmlFor="addressPostcode" className="block text-sm font-medium text-gray-700 mb-2">
                                            Postcode *
                                        </label>
                                        <input
                                            type="text"
                                            id="addressPostcode"
                                            name="addressPostcode"
                                            value={data.addressPostcode}
                                            onChange={(e) => handleFieldChange('addressPostcode', e.target.value)}
                                            className={`w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors ${
                                                validationErrors.addressPostcode ? 'border-red-500 bg-red-50' :
                                                data.addressPostcode ? 'border-green-500 bg-green-50' : 'border-gray-300'
                                            }`}
                                            autoComplete="postal-code"
                                            inputMode="numeric"
                                            placeholder="4-digit postcode"
                                        />
                                        {validationErrors.addressPostcode && (
                                            <p className="mt-1 text-sm text-red-600">{validationErrors.addressPostcode}</p>
                                        )}
                                        {errors.addressPostcode && (
                                            <p className="mt-1 text-sm text-red-600">{errors.addressPostcode}</p>
                                        )}
                                    </div>
                                </div>
                            </div>

                            {/* Special Requirements */}
                            <div>
                                <label htmlFor="specialRequirements" className="block text-sm font-medium text-gray-700 mb-2">
                                    Special Requirements
                                </label>
                                <textarea
                                    id="specialRequirements"
                                    name="specialRequirements"
                                    value={data.specialRequirements}
                                    onChange={e => setData('specialRequirements', e.target.value)}
                                    rows={3}
                                    autoComplete="on"
                                    className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    // To make changes at: Placeholder Special Requirements.
                                    placeholder="Any special requirements, rush orders, specific materials, or additional services needed..."
                                />
                                {errors.specialRequirements && (
                                    <p className="mt-1 text-sm text-red-600">{errors.specialRequirements}</p>
                                )}
                            </div>

                            {/* Submit Button with Progressive Validation */}
                            <div className="text-center">
                                <button
                                    type="submit"
                                    disabled={isSubmitDisabled}
                                    className={submitButtonClasses}
                                >
                                    {processing || isSubmitting ? (
                                        <div className="flex items-center justify-center">
                                            <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Sending...
                                        </div>
                                    ) : (
                                        'Get Quote'
                                    )}
                                </button>
                            </div>
                        </HumanVerificationWrapper>
                        </form>

                        {/* Additional Information */}
                        <div className="mt-8 pt-8 border-t border-gray-200">
                            <h3 className="text-lg font-semibold text-gray-900 mb-4">What Happens Next?</h3>
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div className="text-center">
                                    <div className="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <span className="text-blue-600 font-bold text-lg">1</span>
                                    </div>
                                    <h4 className="font-medium text-gray-900 mb-2">Submit Request</h4>
                                    <p className="text-sm text-gray-600">Fill out the form above with your project details</p>
                                </div>
                                <div className="text-center">
                                    <div className="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <span className="text-blue-600 font-bold text-lg">2</span>
                                    </div>
                                    <h4 className="font-medium text-gray-900 mb-2">Quick Review</h4>
                                    <p className="text-sm text-gray-600">We'll review your requirements within 24 hours</p>
                                </div>
                                <div className="text-center">
                                    <div className="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <span className="text-blue-600 font-bold text-lg">3</span>
                                    </div>
                                    <h4 className="font-medium text-gray-900 mb-2">Detailed Quote</h4>
                                    <p className="text-sm text-gray-600">Receive a comprehensive quote and timeline</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Contact Information */}
            <section className="py-16 bg-gray-100">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 className="text-3xl font-bold text-gray-900 mb-8">
                        Need Immediate Assistance?
                    </h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div className="bg-white rounded-lg p-8 shadow-lg">
                            <div className="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg className="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <h3 className="text-xl font-semibold text-gray-900 mb-2">Call Us</h3>
                            <p className="text-gray-600 mb-4">Speak directly with our printing experts</p>
                            <a 
                                href={`tel:${phone}`} 
                                className="text-blue-600 font-semibold text-lg hover:text-blue-700"
                            >
                                {phone}
                            </a>
                        </div>

                        <div className="bg-white rounded-lg p-8 shadow-lg">
                            <div className="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg className="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 className="text-xl font-semibold text-gray-900 mb-2">Email Us</h3>
                            <p className="text-gray-600 mb-4">Send us a detailed message</p>
                            <a 
                                href={`mailto:${email}`} 
                                className="text-green-600 font-semibold text-lg hover:text-green-700"
                            >
                                {email}
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </Layout>
    );
}
