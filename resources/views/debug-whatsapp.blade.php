<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WhatsApp Debug</title>
    <script src="https://unpkg.com/react@18/umd/react.development.js"></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">WhatsApp Components Debug</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Simple WhatsApp Button -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Simple WhatsApp Button</h2>
                <div id="simple-button"></div>
            </div>
            
            <!-- Simple Mobile WhatsApp Button -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Simple Mobile WhatsApp Button</h2>
                <div id="mobile-button"></div>
            </div>
        </div>
        
        <!-- Test Results -->
        <div class="mt-8 bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Test Results</h2>
            <div id="test-results"></div>
        </div>
    </div>

    <script type="text/babel">
        // Simple WhatsApp Button Component
        const SimpleWhatsAppButton = ({ 
            message = "Hi! I have a printing question. Can you help?",
            className = "",
            variant = "default",
            size = "md"
        }) => {
            const handleClick = () => {
                const phone = '+61449598440';
                const cleanPhone = phone.replace(/[^0-9+]/g, '');
                const encodedMessage = encodeURIComponent(message);
                const url = `https://wa.me/${cleanPhone}?text=${encodedMessage}`;
                window.open(url, '_blank');
            };

            const getSizeClasses = () => {
                switch (size) {
                    case 'sm':
                        return 'px-3 py-2 text-sm';
                    case 'lg':
                        return 'px-6 py-4 text-lg';
                    default:
                        return 'px-4 py-3 text-base';
                }
            };

            const getVariantClasses = () => {
                switch (variant) {
                    case 'outline':
                        return 'border border-green-500 text-green-500 hover:bg-green-500 hover:text-white';
                    case 'ghost':
                        return 'text-green-500 hover:bg-green-100';
                    case 'mobile':
                        return 'bg-[#25D366] text-white';
                    default:
                        return 'bg-green-500 text-white hover:bg-green-600';
                }
            };

            const iconSize = size === 'sm' ? 'w-4 h-4' : 'w-5 h-5';

            return (
                <button
                    onClick={handleClick}
                    className={`inline-flex items-center justify-center font-medium rounded-lg transition-colors duration-200 ${getSizeClasses()} ${getVariantClasses()} ${className}`}
                >
                    <svg className={`${iconSize} mr-2`} fill="currentColor" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.6-3.807-1.6-5.87 0-6.855 5.572-12.427 12.427-12.427S24 5.108 24 11.963c0 6.855-5.572 12.427-12.427 12.427-.625 0-1.246-.056-1.858-.164L.057 24zm6.597-3.807c.339.339.784.508 1.229.508.445 0 .89-.169 1.229-.508l.001-.001c.339-.339.508-.784.508-1.229 0-.445-.169-.89-.508-1.229l-.001-.001c-.339-.339-.784-.508-1.229-.508-.445 0-.89.169-1.229.508l-.001.001c-.339.339-.508.784-.508 1.229 0 .445.169.89.508 1.229zM12 2.057c-5.497 0-9.943 4.446-9.943 9.943 0 2.063.616 4.066 1.657 5.87L.057 24l6.163-1.687c1.804 1.041 3.807 1.6 5.87 1.6 5.497 0 9.943-4.446 9.943-9.943S17.497 2.057 12 2.057zm0 18.857c-1.804 0-3.55-.53-5.04-1.53l-.001-.001c-.339-.339-.508-.784-.508-1.229 0-.445.169-.89.508-1.229l.001-.001c.339-.339.784-.508 1.229-.508.445 0 .89.169 1.229.508l.001.001c.339.339.508.784.508 1.229 0 .445-.169.89-.508 1.229z"/>
                    </svg>
                    <span className="whitespace-nowrap">
                        {variant === 'mobile' ? 'WhatsApp Us' : 'Chat on WhatsApp'}
                    </span>
                </button>
            );
        };

        // Simple Mobile WhatsApp Button Component
        const SimpleMobileWhatsAppButton = ({ className = '' }) => {
            return (
                <div className={`fixed bottom-6 right-6 z-50 md:hidden ${className}`}>
                    <SimpleWhatsAppButton
                        variant="mobile"
                        size="lg"
                        className="w-16 h-16 rounded-full shadow-2xl hover:shadow-3xl"
                        aria-label="Chat on WhatsApp - Mobile"
                    />
                    
                    {/* Pulse animation ring */}
                    <div className="absolute inset-0 w-16 h-16 rounded-full bg-green-500 opacity-20 animate-ping"></div>
                </div>
            );
        };

        // Test Results Component
        const TestResults = () => {
            const [results, setResults] = React.useState([]);
            
            const testAPI = async () => {
                try {
                    const response = await fetch('/whatsapp-api/test');
                    const data = await response.json();
                    setResults(prev => [...prev, { endpoint: '/whatsapp-api/test', data, timestamp: new Date().toLocaleTimeString() }]);
                } catch (error) {
                    setResults(prev => [...prev, { endpoint: '/whatsapp-api/test', error: error.message, timestamp: new Date().toLocaleTimeString() }]);
                }
            };

            const testConfig = async () => {
                try {
                    const response = await fetch('/whatsapp-api/config');
                    const data = await response.json();
                    setResults(prev => [...prev, { endpoint: '/whatsapp-api/config', data, timestamp: new Date().toLocaleTimeString() }]);
                } catch (error) {
                    setResults(prev => [...prev, { endpoint: '/whatsapp-api/config', error: error.message, timestamp: new Date().toLocaleTimeString() }]);
                }
            };

            return (
                <div>
                    <div className="flex space-x-4 mb-4">
                        <button 
                            onClick={testAPI}
                            className="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                        >
                            Test API
                        </button>
                        <button 
                            onClick={testConfig}
                            className="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
                        >
                            Test Config
                        </button>
                    </div>
                    
                    <div className="space-y-2">
                        {results.map((result, index) => (
                            <div key={index} className="p-3 bg-gray-100 rounded text-sm">
                                <div className="font-semibold">{result.endpoint} - {result.timestamp}</div>
                                {result.data ? (
                                    <pre className="mt-1 text-xs overflow-x-auto">{JSON.stringify(result.data, null, 2)}</pre>
                                ) : (
                                    <div className="mt-1 text-red-600">{result.error}</div>
                                )}
                            </div>
                        ))}
                    </div>
                </div>
            );
        };

        // Render components
        ReactDOM.render(<SimpleWhatsAppButton />, document.getElementById('simple-button'));
        ReactDOM.render(<SimpleMobileWhatsAppButton />, document.getElementById('mobile-button'));
        ReactDOM.render(<TestResults />, document.getElementById('test-results'));
    </script>
</body>
</html>
