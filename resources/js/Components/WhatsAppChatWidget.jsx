import React, { useState } from 'react';
import WhatsAppButton from './WhatsAppButton';

const WhatsAppChatWidget = ({ 
    className = '',
    showOnMobile = true,
    showOnDesktop = true 
}) => {
    const [isOpen, setIsOpen] = useState(false);
    const [selectedService, setSelectedService] = useState('');
    const [customMessage, setCustomMessage] = useState('');

    const services = [
        { key: 'business_cards', label: 'Business Cards', icon: '💼' },
        { key: 'banners', label: 'Banners', icon: '🚩' },
        { key: 'flyers', label: 'Flyers', icon: '📄' },
        { key: 'letterheads', label: 'Letterheads', icon: '📝' },
        { key: 'posters', label: 'Posters', icon: '🖼️' },
        { key: 'corflute_signs', label: 'Corflute Signs', icon: '🪧' },
        { key: 'window_graphics', label: 'Window Graphics', icon: '🪟' },
        { key: 'general', label: 'General Inquiry', icon: '❓' },
        { key: 'urgent', label: 'Urgent Request', icon: '⚡' },
    ];

    const handleServiceSelect = (serviceKey) => {
        setSelectedService(serviceKey);
        setCustomMessage('');
    };

    const handleSendMessage = () => {
        const phone = '+61449598440';
        const templates = {
            'business_cards': "Hi! I need a quote for business cards. Can you help?",
            'banners': "Hi! I need banners for an event. What are my options?",
            'flyers': "Hi! I need flyers printed. Can you help with design and printing?",
            'letterheads': "Hi! I need professional letterheads. What are your options?",
            'posters': "Hi! I need posters printed. What sizes and materials do you offer?",
            'corflute_signs': "Hi! I need corflute signs for outdoor advertising. Can you help?",
            'window_graphics': "Hi! I need window graphics for my storefront. What are my options?",
            'general': "Hi! I have a printing question. Can you help?",
            'urgent': "URGENT: I need printing services quickly. Available?",
        };

        const message = customMessage || templates[selectedService] || templates['general'];
        const cleanPhone = phone.replace(/[^0-9+]/g, '');
        const encodedMessage = encodeURIComponent(message);
        const url = `https://wa.me/${cleanPhone}?text=${encodedMessage}`;
        
        window.open(url, '_blank');
        setIsOpen(false);
        setSelectedService('');
        setCustomMessage('');
    };

    const isVisible = (showOnMobile && window.innerWidth < 768) || 
                     (showOnDesktop && window.innerWidth >= 768);

    if (!isVisible) return null;

    return (
        <>
            {/* Chat Widget Toggle Button */}
            <div className={`fixed z-50 bottom-6 right-6 ${className}`}>
                <button
                    onClick={() => setIsOpen(!isOpen)}
                    className="w-16 h-16 bg-[#25D366] text-white rounded-full shadow-2xl hover:shadow-3xl transition-all duration-300 flex items-center justify-center hover:bg-[#1EA952]"
                    aria-label="Open WhatsApp chat widget"
                >
                    <svg className="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.263.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                    </svg>
                </button>

                {/* Pulse animation ring */}
                <div className="absolute inset-0 w-16 h-16 rounded-full bg-[#25D366] opacity-20 animate-ping"></div>
            </div>

            {/* Chat Widget Modal */}
            {isOpen && (
                <div className="fixed inset-0 z-40">
                    {/* Backdrop */}
                    <div 
                        className="absolute inset-0 bg-black bg-opacity-50"
                        onClick={() => setIsOpen(false)}
                    />
                    
                    {/* Widget Content */}
                    <div className="absolute bottom-24 right-6 w-80 bg-white rounded-lg shadow-2xl border border-gray-200 overflow-hidden">
                        {/* Header */}
                        <div className="bg-[#25D366] text-white p-4">
                            <div className="flex items-center justify-between">
                                <div className="flex items-center gap-3">
                                    <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.263.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                    </svg>
                                    <div>
                                        <h3 className="font-semibold">Melbourne Print Hub</h3>
                                        <p className="text-sm opacity-90">Chat with us on WhatsApp</p>
                                    </div>
                                </div>
                                <button
                                    onClick={() => setIsOpen(false)}
                                    className="text-white hover:text-gray-200 transition-colors"
                                >
                                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {/* Content */}
                        <div className="p-4 space-y-4">
                            {/* Service Selection */}
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    What do you need help with?
                                </label>
                                <div className="grid grid-cols-3 gap-2">
                                    {services.map((service) => (
                                        <button
                                            key={service.key}
                                            onClick={() => handleServiceSelect(service.key)}
                                            className={`p-2 text-center rounded-md border transition-colors ${
                                                selectedService === service.key
                                                    ? 'border-[#25D366] bg-[#25D366] text-white'
                                                    : 'border-gray-300 hover:border-gray-400'
                                            }`}
                                        >
                                            <div className="text-lg mb-1">{service.icon}</div>
                                            <div className="text-xs">{service.label}</div>
                                        </button>
                                    ))}
                                </div>
                            </div>

                            {/* Custom Message */}
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    Custom message (optional)
                                </label>
                                <textarea
                                    value={customMessage}
                                    onChange={(e) => setCustomMessage(e.target.value)}
                                    placeholder="Add any specific details..."
                                    className="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#25D366] focus:border-transparent resize-none"
                                    rows="3"
                                />
                            </div>

                            {/* Send Button */}
                            <button
                                onClick={handleSendMessage}
                                disabled={!selectedService}
                                className="w-full bg-[#25D366] text-white py-3 px-4 rounded-md font-medium hover:bg-[#1EA952] transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Send WhatsApp Message
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </>
    );
};

export default WhatsAppChatWidget;
