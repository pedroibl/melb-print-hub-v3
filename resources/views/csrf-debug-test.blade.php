<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSRF Debug Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-center mb-8">CSRF Token Debug Test</h1>
        
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-6">
            <div id="debug-info" class="mb-6 p-4 bg-gray-50 rounded">
                <h2 class="text-lg font-semibold mb-2">Debug Information</h2>
                <div id="debug-content">Loading...</div>
            </div>
            
            <form id="test-form" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" value="Test User" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="test@example.com" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Message</label>
                    <textarea name="message" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">Test message</textarea>
                </div>
                
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">
                    Test Submit
                </button>
            </form>
            
            <div id="result" class="mt-6 p-4 bg-gray-50 rounded hidden">
                <h3 class="text-lg font-semibold mb-2">Result</h3>
                <div id="result-content"></div>
            </div>
        </div>
    </div>

    <script>
        // Load debug info
        fetch('/debug-csrf')
            .then(response => response.json())
            .then(data => {
                document.getElementById('debug-content').innerHTML = `
                    <p><strong>CSRF Token:</strong> ${data.csrf_token.substring(0, 20)}...</p>
                    <p><strong>Session ID:</strong> ${data.session_id.substring(0, 20)}...</p>
                    <p><strong>Session Lifetime:</strong> ${data.session_lifetime} minutes</p>
                    <p><strong>Expire on Close:</strong> ${data.session_expire_on_close ? 'Yes' : 'No'}</p>
                    <p><strong>Same Site:</strong> ${data.session_same_site}</p>
                    <p><strong>Secure:</strong> ${data.session_secure ? 'Yes' : 'No'}</p>
                    <p><strong>HTTP Only:</strong> ${data.session_http_only ? 'Yes' : 'No'}</p>
                    <p><strong>User Agent:</strong> ${data.user_agent}</p>
                    <p><strong>Is Mobile:</strong> ${data.is_mobile ? 'Yes' : 'No'}</p>
                    <p><strong>IP:</strong> ${data.ip}</p>
                `;
            })
            .catch(error => {
                document.getElementById('debug-content').innerHTML = `<p class="text-red-600">Error: ${error.message}</p>`;
            });

        // Handle form submission
        document.getElementById('test-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');
            
            fetch('/get-quote', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                const resultDiv = document.getElementById('result');
                const resultContent = document.getElementById('result-content');
                
                if (response.ok) {
                    resultContent.innerHTML = '<p class="text-green-600">✅ Success! Form submitted successfully.</p>';
                } else if (response.status === 419) {
                    resultContent.innerHTML = '<p class="text-red-600">❌ CSRF Token Mismatch (419 Error)</p>';
                } else {
                    resultContent.innerHTML = `<p class="text-red-600">❌ Error: ${response.status} ${response.statusText}</p>`;
                }
                
                resultDiv.classList.remove('hidden');
            })
            .catch(error => {
                const resultDiv = document.getElementById('result');
                const resultContent = document.getElementById('result-content');
                resultContent.innerHTML = `<p class="text-red-600">❌ Network Error: ${error.message}</p>`;
                resultDiv.classList.remove('hidden');
            });
        });
    </script>
</body>
</html>
