// Shared base class for button styling
const BASE_BUTTON_CLASSES = 'bg-blue-600 text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-blue-700 transition-colors';

/**
 * Hook to determine the submit button's state based on form and reCAPTCHA status
 */
export const useSubmitButtonState = (
    verificationData,
    processing: boolean,
    isSubmitting: boolean,
    isFormValid: boolean
) => {
    const getButtonState = () => {
        if (processing || isSubmitting) {
            return {
                disabled: true,
                text: 'Sending...',
                className: 'opacity-75 cursor-not-allowed',
                showSpinner: true
            };
        }

        if (!isFormValid) {
            return {
                disabled: true,
                text: 'Get My Quote',
                className: 'opacity-50 cursor-not-allowed bg-gray-400 hover:bg-gray-400',
                showSpinner: false
            };
        }

        if (!verificationData?.token) {
            return {
                disabled: true,
                text: 'Verifying...',
                className: 'opacity-75 cursor-wait bg-yellow-600 hover:bg-yellow-600',
                showSpinner: true
            };
        }

        return {
            disabled: false,
            text: 'Get My Quote',
            className: 'opacity-100 cursor-pointer',
            showSpinner: false
        };
    };

    return getButtonState();
};

/**
 * Manages a button element's enabled/disabled state based on reCAPTCHA and form validity
 */
export const manageSubmitButton = (
    submitButton: HTMLButtonElement | null,
    verificationToken: string | null,
    isFormValid: boolean = true
) => {
    if (!submitButton) return;

    const shouldEnable = verificationToken && isFormValid;

    submitButton.disabled = !shouldEnable;
    submitButton.classList.toggle('opacity-50', !shouldEnable);
    submitButton.classList.toggle('cursor-not-allowed', !shouldEnable);
    submitButton.classList.toggle('opacity-100', shouldEnable);
    submitButton.classList.toggle('cursor-pointer', shouldEnable);
};

/**
 * Updates a button's visual state based on status
 */
export const updateButtonState = (
    submitButton: HTMLButtonElement | null,
    state: 'loading' | 'verifying' | 'ready' | 'disabled'
) => {
    if (!submitButton) return;

    const STATES = {
        loading: {
            text: 'Sending...',
            disabled: true,
            extraClasses: 'opacity-75 cursor-not-allowed'
        },
        verifying: {
            text: 'Verifying...',
            disabled: true,
            extraClasses: 'opacity-75 cursor-wait bg-yellow-600 hover:bg-yellow-600'
        },
        ready: {
            text: 'Get My Quote',
            disabled: false,
            extraClasses: 'opacity-100 cursor-pointer'
        },
        disabled: {
            text: 'Get My Quote',
            disabled: true,
            extraClasses: 'opacity-50 cursor-not-allowed bg-gray-400 hover:bg-gray-400'
        }
    };

    const { text, disabled, extraClasses } = STATES[state] || STATES.disabled;

    submitButton.textContent = text;
    submitButton.disabled = disabled;
    submitButton.className = `${BASE_BUTTON_CLASSES} ${extraClasses}`;
};
