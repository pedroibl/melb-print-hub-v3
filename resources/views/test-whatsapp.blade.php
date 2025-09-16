<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WhatsApp Test</title>
    <script src="https://unpkg.com/react@18/umd/react.development.js"></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div id="root"></div>
    
    <script type="text/babel">
        const WhatsAppButton = ({ 
            variant = 'default', 
            template = 'general', 
            customMessage = '', 
            className = '',
            showText = true,
            size = 'md'
        }) => {
            const getWhatsAppUrl = () => {
                const phone = '+61449598440';
                const templates = {
                    'business_cards': "Hi! I need a quote for business cards. Can you help?",
                    'general': "Hi! I have a printing question. Can you help?"
                };
                
                const message = customMessage || templates[template] || templates['general'];
                const cleanPhone = phone.replace(/[^0-9+]/g, '');
                const encodedMessage = encodeURIComponent(message);
                
                return `https://wa.me/${cleanPhone}?text=${encodedMessage}`;
            };

            const handleClick = () => {
                window.open(getWhatsAppUrl(), '_blank');
            };

            return (
                <button
                    onClick={handleClick}
                    className="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600"
                >
                    Chat on WhatsApp
                </button>
            );
        };

        const App = () => {
            return (
                <div className="max-w-4xl mx-auto">
                    <h1 className="text-3xl font-bold text-center mb-8">WhatsApp Component Test</h1>
                    
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div className="bg-white p-6 rounded-lg shadow">
                            <h2 className="text-xl font-semibold mb-4">WhatsApp Button Test</h2>
                            <WhatsAppButton />
                        </div>
                        
                        <div className="bg-white p-6 rounded-lg shadow">
                            <h2 className="text-xl font-semibold mb-4">Business Cards Template</h2>
                            <WhatsAppButton template="business_cards" />
                        </div>
                    </div>
                    
                    <div className="mt-8 text-center">
                        <p className="text-gray-600">Click the buttons above to test WhatsApp integration</p>
                    </div>
                </div>
            );
        };

        ReactDOM.render(<App />, document.getElementById('root'));
    </script>
</body>
</html>
