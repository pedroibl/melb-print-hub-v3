<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WhatsApp Mobile Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">WhatsApp Mobile Test</h1>
        
        <!-- Test the exact same button as in the main app -->
        <div class="fixed bottom-6 right-6 z-[9999]">
            <button 
                onclick="openWhatsApp()"
                class="w-16 h-16 bg-green-500 text-white rounded-full shadow-2xl hover:shadow-3xl hover:bg-green-600 transition-all duration-200 flex items-center justify-center relative z-10"
                aria-label="Chat on WhatsApp"
                style="touch-action: manipulation;"
            >
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.263.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                </svg>
            </button>
            
            <!-- Pulse animation ring -->
            <div class="absolute inset-0 w-16 h-16 rounded-full bg-green-500 opacity-20 animate-ping pointer-events-none"></div>
        </div>

        <!-- Test Results -->
        <div class="mt-8 bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Test Results</h2>
            <div id="test-results" class="p-3 bg-gray-100 rounded text-sm">
                Ready to test WhatsApp button...
            </div>
        </div>
    </div>

    <script>
        function openWhatsApp() {
            const phone = '+61449598440';
            const message = "Hi! I have a printing question. Can you help?";
            const cleanPhone = phone.replace(/[^0-9+]/g, '');
            const encodedMessage = encodeURIComponent(message);
            const url = `https://wa.me/${cleanPhone}?text=${encodedMessage}`;
            
            console.log('Opening WhatsApp:', url);
            
            // Update test results
            document.getElementById('test-results').innerHTML = `
                <div class="p-3 bg-green-100 text-green-800 rounded">
                    ✅ WhatsApp opened! URL: ${url}
                </div>
            `;
            
            // Try different methods to open WhatsApp
            try {
                // Method 1: Direct window.open
                window.open(url, '_blank');
                
                // Method 2: Try to open in same window if _blank doesn't work
                setTimeout(() => {
                    window.location.href = url;
                }, 100);
                
            } catch (error) {
                console.error('Error opening WhatsApp:', error);
                document.getElementById('test-results').innerHTML = `
                    <div class="p-3 bg-red-100 text-red-800 rounded">
                        ❌ Error opening WhatsApp: ${error.message}
                    </div>
                `;
            }
        }

        // Add touch event listener for better mobile support
        document.addEventListener('DOMContentLoaded', function() {
            const button = document.querySelector('button');
            
            // Add touch event
            button.addEventListener('touchstart', function(e) {
                e.preventDefault();
                console.log('Touch detected on WhatsApp button');
                openWhatsApp();
            });
            
            // Add click event
            button.addEventListener('click', function(e) {
                console.log('Click detected on WhatsApp button');
                openWhatsApp();
            });
        });
    </script>
</body>
</html>
