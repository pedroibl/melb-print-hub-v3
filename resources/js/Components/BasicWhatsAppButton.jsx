import React from 'react';

const BasicWhatsAppButton = () => {
    const handleClick = () => {
        const phone = '+61449598440';
        const message = "Hi! I have a printing question. Can you help?";
        const cleanPhone = phone.replace(/[^0-9+]/g, '');
        const encodedMessage = encodeURIComponent(message);
        const url = `https://wa.me/${cleanPhone}?text=${encodedMessage}`;
        
        console.log('WhatsApp button clicked, opening:', url);
        window.open(url, '_blank');
    };

    return (
        <button
            onClick={handleClick}
            className="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
            style={{ border: '2px solid red' }} // Add visible styling to debug
        >
            WhatsApp Chat
        </button>
    );
};

export default BasicWhatsAppButton;
