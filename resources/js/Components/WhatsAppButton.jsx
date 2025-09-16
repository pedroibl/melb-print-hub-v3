import React from 'react';

const WhatsAppButton = ({ variant = 'default', size = 'sm' }) => {
    const handleClick = () => {
        const phone = '+61449598440';
        const message = "Hi! I have a printing question. Can you help?";
        const cleanPhone = phone.replace(/[^0-9+]/g, '');
        const encodedMessage = encodeURIComponent(message);
        const url = `https://wa.me/${cleanPhone}?text=${encodedMessage}`;
        window.open(url, '_blank');
    };

    return (
        <button
            onClick={handleClick}
            className="bg-green-500 text-white px-3 py-2 text-sm rounded-lg hover:bg-green-600"
        >
            Chat on WhatsApp
        </button>
    );
};

export default WhatsAppButton;
