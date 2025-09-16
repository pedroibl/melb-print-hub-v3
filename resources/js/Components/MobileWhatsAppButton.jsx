import React from 'react';
import WhatsAppButton from './WhatsAppButton';

const MobileWhatsAppButton = ({ className = '' }) => {
    return (
        <div className={`fixed bottom-6 right-6 z-50 md:hidden ${className}`}>
            <WhatsAppButton
                variant="mobile"
                size="lg"
                showText={false}
                className="w-16 h-16 rounded-full shadow-2xl hover:shadow-3xl"
                aria-label="Chat on WhatsApp - Mobile"
            />
            
            {/* Pulse animation ring */}
            <div className="absolute inset-0 w-16 h-16 rounded-full bg-[#25D366] opacity-20 animate-ping"></div>
        </div>
    );
};

export default MobileWhatsAppButton;
