<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Email Mobile Test - Melbourne Print Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-8 px-4">
        <div class="max-w-md mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">📧 Email Mobile Test</h1>
                <p class="text-gray-600">Test email functionality on mobile devices</p>
            </div>

            <!-- Test Form -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Email Test Form</h2>
                
                <form id="emailTestForm" class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Full Name *
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="Your full name"
                            required
                        />
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email Address *
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="your.email@example.com"
                            required
                        />
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1">
                            Test Message *
                        </label>
                        <textarea
                            id="message"
                            name="message"
                            rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="Enter your test message here..."
                            required
                        ></textarea>
                    </div>

                    <button
                        type="submit"
                        id="submitBtn"
                        class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 transition-colors"
                    >
                        Test Email Sending
                    </button>
                </form>

                <!-- Loading State -->
                <div id="loadingState" class="hidden mt-4 text-center">
                    <div class="inline-flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-green-600 font-medium">Testing Email...</span>
                    </div>
                </div>

                <!-- Results -->
                <div id="results" class="hidden mt-6 p-4 rounded-lg">
                    <h3 id="resultTitle" class="text-lg font-semibold mb-2"></h3>
                    <p id="resultMessage" class="text-sm mb-3"></p>
                    <div id="resultDetails" class="text-xs text-gray-600 space-y-1"></div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="mt-6 bg-blue-50 rounded-lg p-4">
                <h3 class="font-semibold text-blue-900 mb-2">📱 Mobile Testing Instructions:</h3>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• Fill out all fields above</li>
                    <li>• Tap "Test Email Sending"</li>
                    <li>• Check your email for the test message</li>
                    <li>• Review the results below</li>
                </ul>
            </div>

            <!-- Back Link -->
            <div class="mt-6 text-center">
                <a href="/email-testing" class="text-blue-600 hover:text-blue-700 font-medium">
                    ← Back to Email Testing Dashboard
                </a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('emailTestForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const loadingState = document.getElementById('loadingState');
            const results = document.getElementById('results');
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.textContent = 'Testing...';
            loadingState.classList.remove('hidden');
            results.classList.add('hidden');
            
            // Get form data
            const formData = new FormData(this);
            const data = {
                name: formData.get('name'),
                email: formData.get('email'),
                message: formData.get('message')
            };
            
            try {
                const response = await fetch('/email-testing/mobile-test-submit', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                // Show results
                const resultTitle = document.getElementById('resultTitle');
                const resultMessage = document.getElementById('resultMessage');
                const resultDetails = document.getElementById('resultDetails');
                
                if (result.success) {
                    resultTitle.textContent = '✅ Test Successful';
                    resultTitle.className = 'text-lg font-semibold mb-2 text-green-800';
                    resultMessage.textContent = result.message;
                    resultMessage.className = 'text-sm mb-3 text-green-700';
                    
                    let detailsHtml = '';
                    if (result.details) {
                        detailsHtml += `<div><strong>Platform:</strong> ${result.details.platform}</div>`;
                        detailsHtml += `<div><strong>IP Address:</strong> ${result.details.ip}</div>`;
                        detailsHtml += `<div><strong>Contact ID:</strong> ${result.details.contact_message_id}</div>`;
                        detailsHtml += `<div><strong>Email Sent:</strong> ${result.details.email_sent ? 'Yes' : 'No'}</div>`;
                    }
                    resultDetails.innerHTML = detailsHtml;
                } else {
                    resultTitle.textContent = '❌ Test Failed';
                    resultTitle.className = 'text-lg font-semibold mb-2 text-red-800';
                    resultMessage.textContent = result.message;
                    resultMessage.className = 'text-sm mb-3 text-red-700';
                    
                    let detailsHtml = '';
                    if (result.details && result.details.error) {
                        detailsHtml += `<div><strong>Error:</strong> ${result.details.error}</div>`;
                    }
                    resultDetails.innerHTML = detailsHtml;
                }
                
                results.classList.remove('hidden');
                
            } catch (error) {
                // Show error
                const resultTitle = document.getElementById('resultTitle');
                const resultMessage = document.getElementById('resultMessage');
                
                resultTitle.textContent = '❌ Network Error';
                resultTitle.className = 'text-lg font-semibold mb-2 text-red-800';
                resultMessage.textContent = 'Failed to connect to server. Please check your internet connection.';
                resultMessage.className = 'text-sm mb-3 text-red-700';
                
                results.classList.remove('hidden');
            } finally {
                // Reset button
                submitBtn.disabled = false;
                submitBtn.textContent = 'Test Email Sending';
                loadingState.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
