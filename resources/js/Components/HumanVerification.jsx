import { useEffect, useRef, useState, useCallback } from 'react';

// reCAPTCHA v2 Component (Visible checkbox)
export const RecaptchaV2 = ({ siteKey, onVerify }) => {
    const recaptchaRef = useRef(null);
    const [isReady, setIsReady] = useState(false);
    const [widgetId, setWidgetId] = useState(null);
    const [error, setError] = useState(null);
    const isMounted = useRef(true);
    
    const cleanup = useCallback(() => {
        if (widgetId !== null && window.grecaptcha && window.grecaptcha.reset) {
            try {
                window.grecaptcha.reset(widgetId);
            } catch (e) {
                console.log('Error resetting reCAPTCHA:', e);
            }
        }
    }, [widgetId]);

    const initializeRecaptcha = useCallback(() => {
        if (!isMounted.current || !recaptchaRef.current || widgetId !== null) {
            return;
        }

        if (!window.grecaptcha || !window.grecaptcha.render) {
            setTimeout(initializeRecaptcha, 100);
            return;
        }

        try {
            console.log('Rendering reCAPTCHA v2 widget');
            const id = window.grecaptcha.render(recaptchaRef.current, {
                sitekey: siteKey,
                callback: (token) => {
                    console.log('✅ reCAPTCHA v2 completed');
                    onVerify({ token, type: 'recaptcha-v2', verified: true });
                },
                'expired-callback': () => {
                    console.log('⏰ reCAPTCHA v2 expired');
                    onVerify({ token: null, type: 'recaptcha-v2', verified: false });
                },
                'error-callback': (error) => {
                    console.error('❌ reCAPTCHA v2 error:', error);
                    setError('reCAPTCHA verification failed');
                    onVerify({ token: null, type: 'recaptcha-v2', verified: false, error });
                }
            });

            setWidgetId(id);
            setIsReady(true);
            setError(null);
            console.log('✅ reCAPTCHA v2 widget rendered successfully');

        } catch (error) {
            console.error('❌ Error initializing reCAPTCHA v2:', error);
            setError(error.message || 'Failed to load reCAPTCHA');
            onVerify({ token: null, type: 'recaptcha-v2', verified: false, error: error.message });
        }
    }, [siteKey, onVerify, widgetId]);

    const loadScript = useCallback(() => {
        if (window.grecaptcha) {
            initializeRecaptcha();
            return;
        }

        const script = document.createElement('script');
        script.src = 'https://www.google.com/recaptcha/api.js';
        script.async = true;
        script.defer = true;
        
        script.onload = () => {
            console.log('reCAPTCHA script loaded');
            setTimeout(initializeRecaptcha, 100);
        };
        
        script.onerror = () => {
            setError('Failed to load reCAPTCHA script');
        };
        
        document.head.appendChild(script);
    }, [initializeRecaptcha]);

    useEffect(() => {
        isMounted.current = true;
        
        if (!siteKey) {
            setError('No reCAPTCHA site key provided');
            return;
        }

        loadScript();

        return () => {
            isMounted.current = false;
            cleanup();
        };
    }, [siteKey, loadScript, cleanup]);

    if (error) {
        return (
            <div style={{
                border: '2px solid #f44336',
                borderRadius: '4px',
                padding: '15px',
                background: '#ffeaea',
                color: '#d32f2f',
                margin: '10px 0'
            }}>
                <strong>❌ reCAPTCHA Error:</strong> {error}
            </div>
        );
    }

    return (
        <div style={{ margin: '20px 0' }}>
            <div ref={recaptchaRef} className="g-recaptcha" />
            {!isReady && !error && (
                <div style={{
                    padding: '15px',
                    color: '#666',
                    background: '#f5f5f5',
                    border: '1px solid #ddd',
                    borderRadius: '4px',
                    textAlign: 'center'
                }}>
                    🔄 Loading reCAPTCHA...
                </div>
            )}
        </div>
    );
};

// reCAPTCHA v3 Component (Invisible - background verification)
export const RecaptchaV3 = ({ siteKey, action = 'submit', onVerify }) => {
    const [isReady, setIsReady] = useState(false);
    const isMounted = useRef(true);
    
    const executeRecaptcha = useCallback(() => {
        if (!window.grecaptcha || !window.grecaptcha.ready) {
            console.log('reCAPTCHA v3 not ready');
            return;
        }

        window.grecaptcha.ready(() => {
            window.grecaptcha.execute(siteKey, { action })
                .then(token => {
                    console.log('✅ reCAPTCHA v3 token received');
                    onVerify({ token, type: 'recaptcha-v3', action, verified: true });
                })
                .catch(error => {
                    console.error('❌ reCAPTCHA v3 error:', error);
                    onVerify({ token: null, type: 'recaptcha-v3', verified: false, error });
                });
        });
    }, [siteKey, action, onVerify]);

    const loadScript = useCallback(() => {
        if (window.grecaptcha) {
            setIsReady(true);
            return;
        }

        const script = document.createElement('script');
        script.src = `https://www.google.com/recaptcha/api.js?render=${siteKey}`;
        script.async = true;
        script.defer = true;
        
        script.onload = () => {
            console.log('reCAPTCHA v3 script loaded');
            setIsReady(true);
        };
        
        script.onerror = () => {
            console.error('Failed to load reCAPTCHA v3 script');
        };
        
        document.head.appendChild(script);
    }, [siteKey]);

    useEffect(() => {
        isMounted.current = true;
        
        if (!siteKey) {
            console.error('No reCAPTCHA v3 site key provided');
            return;
        }

        loadScript();

        return () => {
            isMounted.current = false;
        };
    }, [siteKey, loadScript]);

    // Auto-execute on mount (invisible verification)
    useEffect(() => {
        if (isReady && isMounted.current) {
            executeRecaptcha();
        }
    }, [isReady, executeRecaptcha]);

    // Expose execute function for manual triggering
    useEffect(() => {
        if (isReady) {
            window.executeRecaptcha = executeRecaptcha;
        }
    }, [isReady, executeRecaptcha]);

    return null; // v3 is invisible
};

// hCaptcha Component
export const HCaptcha = ({ siteKey, onVerify }) => {
    const hcaptchaRef = useRef(null);
    const [isReady, setIsReady] = useState(false);
    const [widgetId, setWidgetId] = useState(null);
    const isMounted = useRef(true);
    
    const initializeHCaptcha = useCallback(() => {
        if (!isMounted.current || !hcaptchaRef.current || widgetId !== null) {
            return;
        }

        if (!window.hcaptcha) {
            setTimeout(initializeHCaptcha, 100);
            return;
        }

        try {
            const id = window.hcaptcha.render(hcaptchaRef.current, {
                sitekey: siteKey,
                callback: (token) => {
                    console.log('✅ hCaptcha verified');
                    onVerify({ token, type: 'hcaptcha', verified: true });
                },
                'expired-callback': () => {
                    console.log('⏰ hCaptcha expired');
                    onVerify({ token: null, type: 'hcaptcha', verified: false });
                },
                'error-callback': (error) => {
                    console.error('❌ hCaptcha error:', error);
                    onVerify({ token: null, type: 'hcaptcha', verified: false, error });
                }
            });
            
            setWidgetId(id);
            setIsReady(true);
            console.log('✅ hCaptcha widget rendered');
            
        } catch (error) {
            console.error('❌ Error initializing hCaptcha:', error);
        }
    }, [siteKey, onVerify, widgetId]);

    const loadScript = useCallback(() => {
        if (window.hcaptcha) {
            initializeHCaptcha();
            return;
        }

        const script = document.createElement('script');
        script.src = 'https://js.hcaptcha.com/1/api.js';
        script.async = true;
        script.defer = true;
        
        script.onload = () => {
            console.log('hCaptcha script loaded');
            setTimeout(initializeHCaptcha, 100);
        };
        
        document.head.appendChild(script);
    }, [initializeHCaptcha]);

    useEffect(() => {
        isMounted.current = true;
        
        if (!siteKey) {
            return;
        }

        loadScript();

        return () => {
            isMounted.current = false;
            if (widgetId !== null && window.hcaptcha && window.hcaptcha.reset) {
                try {
                    window.hcaptcha.reset(widgetId);
                } catch (e) {
                    console.log('Error resetting hCaptcha:', e);
                }
            }
        };
    }, [siteKey, loadScript, widgetId]);

    return (
        <div style={{ margin: '20px 0' }}>
            <div ref={hcaptchaRef} className="h-captcha" />
            {!isReady && (
                <div style={{
                    padding: '15px',
                    color: '#666',
                    background: '#f5f5f5',
                    border: '1px solid #ddd',
                    borderRadius: '4px',
                    textAlign: 'center'
                }}>
                    🔄 Loading hCaptcha...
                </div>
            )}
        </div>
    );
};

// Honeypot Field Component
export const HoneypotField = ({ name = 'website' }) => {
    return (
        <input
            type="text"
            name={name}
            style={{
                position: 'absolute',
                left: '-9999px',
                top: '-9999px',
                width: '1px',
                height: '1px',
                opacity: 0,
                pointerEvents: 'none'
            }}
            tabIndex="-1"
            autoComplete="off"
            aria-hidden="true"
        />
    );
};

// Form Timer Component
export const FormTimer = ({ onTimeSet }) => {
    useEffect(() => {
        const startTime = Math.floor(Date.now() / 1000);
        onTimeSet(startTime);
    }, [onTimeSet]);
    
    return null;
};

// Enhanced Human Verification Wrapper
export const HumanVerificationWrapper = ({ 
    children, 
    verificationType = 'recaptcha-v2', // Default to visible v2
    version = 'v2', // v2 or v3 for reCAPTCHA
    recaptchaSiteKey,
    hcaptchaSiteKey,
    action = 'submit', // For reCAPTCHA v3
    onVerificationComplete,
    debug = false,
    showBeforeSubmit = true // Control positioning
}) => {
    const [verificationToken, setVerificationToken] = useState(null);
    const [formStartTime, setFormStartTime] = useState(null);
    const [verificationStatus, setVerificationStatus] = useState('pending');
    const [isVerified, setIsVerified] = useState(false);
    
    const handleVerification = (result) => {
        if (debug) {
            console.log('🔍 Verification result:', result);
        }
        
        setVerificationToken(result.token);
        setIsVerified(result.verified || false);
        setVerificationStatus(result.verified ? 'verified' : 'failed');
        
        if (onVerificationComplete) {
            onVerificationComplete({
                ...result,
                formStartTime,
                verificationType: `${verificationType}-${version}`
            });
        }
    };
    
    const handleTimeSet = (startTime) => {
        setFormStartTime(startTime);
    };

    const renderCaptcha = () => {
        if (verificationType === 'none') {
            return null;
        }
        if (verificationType === 'recaptcha' && version === 'v2' && recaptchaSiteKey) {
            return (
                <RecaptchaV2 
                    siteKey={recaptchaSiteKey}
                    onVerify={handleVerification}
                />
            );
        }
        
        if (verificationType === 'recaptcha' && version === 'v3' && recaptchaSiteKey) {
            return (
                <RecaptchaV3 
                    siteKey={recaptchaSiteKey}
                    action={action}
                    onVerify={handleVerification}
                />
            );
        }
        
        if (verificationType === 'hcaptcha' && hcaptchaSiteKey) {
            return (
                <HCaptcha 
                    siteKey={hcaptchaSiteKey}
                    onVerify={handleVerification}
                />
            );
        }
        
        return null;
    };

    // Validation
    if (verificationType === 'none') {
        return (
            <div>
                <FormTimer onTimeSet={handleTimeSet} />
                <HoneypotField />
                {children}
            </div>
        );
    }

    if (verificationType === 'recaptcha' && !recaptchaSiteKey) {
        console.error('❌ reCAPTCHA site key is required');
        return (
            <div style={{ color: 'red', padding: '15px', border: '1px solid red', borderRadius: '4px' }}>
                ❌ Error: reCAPTCHA site key is missing
            </div>
        );
    }
    
    if (verificationType === 'hcaptcha' && !hcaptchaSiteKey) {
        console.error('❌ hCaptcha site key is required');
        return (
            <div style={{ color: 'red', padding: '15px', border: '1px solid red', borderRadius: '4px' }}>
                ❌ Error: hCaptcha site key is missing
            </div>
        );
    }
    
    return (
        <div>
            <FormTimer onTimeSet={handleTimeSet} />
            <HoneypotField />
            
            {debug && (
                <div style={{ 
                    background: '#e3f2fd', 
                    padding: '15px', 
                    margin: '10px 0',
                    borderRadius: '4px',
                    fontSize: '12px',
                    fontFamily: 'monospace'
                }}>
                    <strong>🔍 Debug Info:</strong><br />
                    Type: {verificationType} {version}<br />
                    Site Key: {recaptchaSiteKey?.substring(0, 20)}...<br />
                    Status: {verificationStatus}<br />
                    Verified: {isVerified ? 'Yes' : 'No'}<br />
                    Has Token: {verificationToken ? 'Yes' : 'No'}<br />
                    Domain: {typeof window !== 'undefined' ? window.location.hostname : 'N/A'}
                </div>
            )}
            
            {/* Render CAPTCHA before children (before submit button) */}
            {showBeforeSubmit && renderCaptcha()}
            
            {/* Form content */}
            {children}
            
            {/* Render CAPTCHA after children (after submit button) if showBeforeSubmit is false */}
            {!showBeforeSubmit && renderCaptcha()}
        </div>
    );
};