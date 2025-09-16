import React, { useState } from 'react';
import WhatsAppButton from './WhatsAppButton';

const WhatsAppFloatingButton = ({ 
    position = 'bottom-right',
    className = '',
    showQuickActions = true 
}) => {
    const [isExpanded, setIsExpanded] = useState(false);

    const getPositionClasses = () => {
        switch (position) {
            case 'bottom-left':
                return 'bottom-6 left-6';
            case 'bottom-center':
                return 'bottom-6 left-1/2 transform -translate-x-1/2';
            case 'top-right':
                return 'top-6 right-6';
            case 'top-left':
                return 'top-6 left-6';
            case 'top-center':
                return 'top-6 left-1/2 transform -translate-x-1/2';
            default:
                return 'bottom-6 right-6';
        }
    };

    const quickActions = [
        { label: 'Quick Quote', template: 'quote', icon: '💬' },
        { label: 'Urgent Request', template: 'urgent', icon: '⚡' },
        { label: 'Design Help', template: 'design_consultation', icon: '🎨' },
        { label: 'Order Status', template: 'order_status', icon: '📋' },
    ];

    return (
        <div className={`fixed z-50 ${getPositionClasses()} ${className}`}>
            {/* Main WhatsApp Button */}
            <div className="relative">
                <WhatsAppButton
                    variant="mobile"
                    size="lg"
                    showText={false}
                    className="w-16 h-16 rounded-full shadow-2xl hover:shadow-3xl transition-all duration-300"
                    onClick={() => setIsExpanded(!isExpanded)}
                />
                
                {/* Pulse animation ring */}
                <div className="absolute inset-0 w-16 h-16 rounded-full bg-[#25D366] opacity-20 animate-ping"></div>
            </div>

            {/* Quick Actions Menu */}
            {showQuickActions && isExpanded && (
                <div className="absolute bottom-20 right-0 bg-white rounded-lg shadow-xl border border-gray-200 p-2 min-w-[200px]">
                    <div className="space-y-2">
                        {quickActions.map((action, index) => (
                            <button
                                key={index}
                                onClick={() => {
                                    setIsExpanded(false);
                                    // Open WhatsApp with specific template
                                    const phone = '+61449598440';
                                    const templates = {
                                        'quote': "Hi! I need a quote for printing services. Can you help?",
                                        'urgent': "URGENT: I need printing services quickly. Available?",
                                        'design_consultation': "Hi! I need help with design for my printing project. Any tips?",
                                        'order_status': "Hi! Can I get an update on my order?"
                                    };
                                    const message = templates[action.template] || templates['quote'];
                                    const cleanPhone = phone.replace(/[^0-9+]/g, '');
                                    const encodedMessage = encodeURIComponent(message);
                                    const url = `https://wa.me/${cleanPhone}?text=${encodedMessage}`;
                                    window.open(url, '_blank');
                                }}
                                className="w-full flex items-center gap-3 px-3 py-2 text-left text-gray-700 hover:bg-gray-50 rounded-md transition-colors duration-200"
                            >
                                <span className="text-lg">{action.icon}</span>
                                <span className="text-sm font-medium">{action.label}</span>
                            </button>
                        ))}
                    </div>
                    
                    {/* Close button */}
                    <button
                        onClick={() => setIsExpanded(false)}
                        className="w-full mt-2 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-md transition-colors duration-200"
                    >
                        Close
                    </button>
                </div>
            )}

            {/* Backdrop to close menu */}
            {isExpanded && (
                <div 
                    className="fixed inset-0 z-40"
                    onClick={() => setIsExpanded(false)}
                />
            )}
        </div>
    );
};

export default WhatsAppFloatingButton;
