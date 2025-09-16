import React from 'react';
import WhatsAppButton from './WhatsAppButton';

const ServiceWhatsAppButton = ({ 
    service, 
    variant = 'default', 
    size = 'md',
    className = '',
    showText = true 
}) => {
    const getServiceTemplate = () => {
        const templates = {
            'business_cards': 'business_cards',
            'banners': 'banners',
            'flyers': 'flyers',
            'letterheads': 'letterheads',
            'posters': 'posters',
            'corflute_signs': 'corflute_signs',
            'window_graphics': 'window_graphics',
            'pull_up_banner': 'banners',
            'teardrop_banner': 'banners',
            'vinyl_banner': 'banners',
            'mesh_banner': 'banners',
            'alu_panel': 'corflute_signs',
            'lightbox_fabric': 'window_graphics',
            'media_wall': 'window_graphics',
            'signflute': 'corflute_signs',
            'vinyl_stickers': 'corflute_signs',
            'yupo_poster': 'posters'
        };

        return templates[service] || 'general';
    };

    return (
        <WhatsAppButton
            variant={variant}
            size={size}
            template={getServiceTemplate()}
            className={className}
            showText={showText}
        />
    );
};

export default ServiceWhatsAppButton;
