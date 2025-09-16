import { useState, useEffect } from 'react';

export const EnhancedSubmitButton = ({ 
    children, 
    onVerificationComplete, 
    isFormValid, 
    processing, 
    isSubmitting,
    className = "bg-blue-600 text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
}) => {
    const [buttonState, setButtonState] = useState('disabled'); // 'disabled', 'waiting', 'ready', 'loading'
    const [verificationToken, setVerificationToken] = useState(null);

    // Update button state based on verification and form state
    useEffect(() => {
        if (processing || isSubmitting) {
            setButtonState('loading');
        } else if (!isFormValid) {
            setButtonState('disabled');
        } else if (!verificationToken) {
            setButtonState('waiting');
        } else {
            setButtonState('ready');
        }
    }, [processing, isSubmitting, isFormValid, verificationToken]);

    // Handle verification completion
    const handleVerificationComplete = (verificationInfo) => {
        setVerificationToken(verificationInfo.token);
        if (onVerificationComplete) {
            onVerificationComplete(verificationInfo);
        }
    };

    // Get button content based on state
    const getButtonContent = () => {
        switch (buttonState) {
            case 'loading':
                return (
                    <div className="flex items-center justify-center">
                        <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                            <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Sending...
                    </div>
                );
            case 'waiting':
                return (
                    <div className="flex items-center justify-center">
                        <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                            <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Verifying...
                    </div>
                );
            case 'ready':
                return children;
            default:
                return children;
        }
    };

    // Get button classes based on state
    const getButtonClasses = () => {
        const baseClasses = className;
        
        switch (buttonState) {
            case 'disabled':
                return `${baseClasses} opacity-50 cursor-not-allowed bg-gray-400 hover:bg-gray-400`;
            case 'waiting':
                return `${baseClasses} opacity-75 cursor-wait bg-yellow-600 hover:bg-yellow-600`;
            case 'ready':
                return `${baseClasses} opacity-100 cursor-pointer`;
            case 'loading':
                return `${baseClasses} opacity-75 cursor-not-allowed`;
            default:
                return baseClasses;
        }
    };

    return (
        <button
            type="submit"
            disabled={buttonState !== 'ready'}
            className={getButtonClasses()}
        >
            {getButtonContent()}
        </button>
    );
};

// Usage example in your Quote.jsx:
/*
import { EnhancedSubmitButton } from '@/Components/EnhancedSubmitButton';

// In your form:
<EnhancedSubmitButton
    isFormValid={isFormValid()}
    processing={processing}
    isSubmitting={isSubmitting}
    onVerificationComplete={handleVerificationComplete}
>
    Get My Quote
</EnhancedSubmitButton>
*/
