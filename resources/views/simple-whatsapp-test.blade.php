<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simple WhatsApp Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Simple WhatsApp Button Test</h1>
        
        <!-- Test 1: Basic HTML Button -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Test 1: Basic HTML Button</h2>
            <button 
                onclick="openWhatsApp()"
                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
            >
                Chat on WhatsApp (HTML)
            </button>
        </div>

        <!-- Test 2: Inline JavaScript -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Test 2: Inline JavaScript</h2>
            <div id="js-button"></div>
        </div>

        <!-- Test 3: Console Log Test -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Test 3: Console Log Test</h2>
            <button 
                onclick="testConsole()"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
            >
                Test Console Log
            </button>
        </div>

        <!-- Test Results -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Test Results</h2>
            <div id="test-results"></div>
        </div>
    </div>

    <script>
        // Test 1: Basic WhatsApp function
        function openWhatsApp() {
            const phone = '+61449598440';
            const message = "Hi! I have a printing question. Can you help?";
            const cleanPhone = phone.replace(/[^0-9+]/g, '');
            const encodedMessage = encodeURIComponent(message);
            const url = `https://wa.me/${cleanPhone}?text=${encodedMessage}`;
            
            console.log('Opening WhatsApp URL:', url);
            window.open(url, '_blank');
            
            // Update test results
            document.getElementById('test-results').innerHTML = `
                <div class="p-3 bg-green-100 text-green-800 rounded">
                    ✅ WhatsApp opened successfully! URL: ${url}
                </div>
            `;
        }

        // Test 2: Dynamic button creation
        function createJSButton() {
            const button = document.createElement('button');
            button.className = 'bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600';
            button.textContent = 'Chat on WhatsApp (JS)';
            button.onclick = openWhatsApp;
            
            document.getElementById('js-button').appendChild(button);
        }

        // Test 3: Console test
        function testConsole() {
            console.log('Console test working!');
            document.getElementById('test-results').innerHTML = `
                <div class="p-3 bg-blue-100 text-blue-800 rounded">
                    ✅ Console test working! Check browser console.
                </div>
            `;
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Page loaded, creating JS button...');
            createJSButton();
            
            document.getElementById('test-results').innerHTML = `
                <div class="p-3 bg-gray-100 text-gray-800 rounded">
                    ℹ️ Page loaded successfully. All tests ready.
                </div>
            `;
        });
    </script>
</body>
</html>
