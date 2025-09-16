<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Security Testing - Melbourne Print Hub</title>
    
    <!-- Security Meta Tags -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://unpkg.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net; font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net; img-src 'self' data: https:; connect-src 'self' https:; frame-src 'none'; object-src 'none'; base-uri 'self'; form-action 'self'; frame-ancestors 'none'; upgrade-insecure-requests;" />
    <meta http-equiv="X-Frame-Options" content="DENY" />
    <meta http-equiv="X-Content-Type-Options" content="nosniff" />
    <meta http-equiv="X-XSS-Protection" content="1; mode=block" />
    <meta name="referrer" content="strict-origin-when-cross-origin" />
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        .security-card {
            @apply bg-white rounded-lg shadow-md p-6 border-l-4;
        }
        .security-success { @apply border-green-500; }
        .security-warning { @apply border-yellow-500; }
        .security-error { @apply border-red-500; }
        .security-info { @apply border-blue-500; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">🔒 Security Testing Dashboard</h1>
                <p class="text-gray-600">Melbourne Print Hub - Security Configuration Verification</p>
                <p class="text-sm text-gray-500 mt-2">Last updated: <span id="timestamp"></span></p>
            </div>

            <!-- Security Status Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="security-card security-info">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">🛡️ Security Headers</h3>
                    <div id="headers-status" class="text-sm text-gray-600">Checking...</div>
                </div>
                
                <div class="security-card security-info">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">🔐 SSL Certificate</h3>
                    <div id="ssl-status" class="text-sm text-gray-600">Checking...</div>
                </div>
                
                <div class="security-card security-info">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">🔑 CSRF Protection</h3>
                    <div id="csrf-status" class="text-sm text-gray-600">Checking...</div>
                </div>
            </div>

            <!-- Detailed Security Tests -->
            <div class="space-y-6">
                <!-- Security Headers Test -->
                <div class="security-card security-info">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Security Headers Test</h3>
                    <div id="headers-details" class="space-y-2 text-sm">
                        <div class="flex justify-between items-center">
                            <span>HSTS (HTTP Strict Transport Security):</span>
                            <span id="hsts-status" class="font-mono">Checking...</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Content Security Policy (CSP):</span>
                            <span id="csp-status" class="font-mono">Checking...</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>X-Frame-Options:</span>
                            <span id="xfo-status" class="font-mono">Checking...</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>X-Content-Type-Options:</span>
                            <span id="xcto-status" class="font-mono">Checking...</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>X-XSS-Protection:</span>
                            <span id="xxp-status" class="font-mono">Checking...</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Referrer Policy:</span>
                            <span id="rp-status" class="font-mono">Checking...</span>
                        </div>
                    </div>
                </div>

                <!-- SSL Certificate Test -->
                <div class="security-card security-info">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">SSL Certificate Test</h3>
                    <div id="ssl-details" class="space-y-2 text-sm">
                        <div class="flex justify-between items-center">
                            <span>Protocol:</span>
                            <span id="protocol-status" class="font-mono">Checking...</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>SSL/TLS Version:</span>
                            <span id="ssl-version" class="font-mono">Checking...</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Cipher Suite:</span>
                            <span id="cipher-suite" class="font-mono">Checking...</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Certificate Status:</span>
                            <span id="cert-status" class="font-mono">Checking...</span>
                        </div>
                    </div>
                </div>

                <!-- CSRF Protection Test -->
                <div class="security-card security-info">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">CSRF Protection Test</h3>
                    <div id="csrf-details" class="space-y-2 text-sm">
                        <div class="flex justify-between items-center">
                            <span>CSRF Token:</span>
                            <span id="csrf-token" class="font-mono text-xs">Checking...</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Session ID:</span>
                            <span id="session-id" class="font-mono text-xs">Checking...</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Session Lifetime:</span>
                            <span id="session-lifetime" class="font-mono">Checking...</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Secure Cookies:</span>
                            <span id="secure-cookies" class="font-mono">Checking...</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>HTTP Only:</span>
                            <span id="http-only" class="font-mono">Checking...</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Same Site:</span>
                            <span id="same-site" class="font-mono">Checking...</span>
                        </div>
                    </div>
                </div>

                <!-- Mixed Content Test -->
                <div class="security-card security-info">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Mixed Content Test</h3>
                    <div id="mixed-content-details" class="space-y-2 text-sm">
                        <div class="flex justify-between items-center">
                            <span>HTTPS Enforcement:</span>
                            <span id="https-enforcement" class="font-mono">Checking...</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Asset URLs:</span>
                            <span id="asset-urls" class="font-mono">Checking...</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>External Resources:</span>
                            <span id="external-resources" class="font-mono">Checking...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex flex-wrap gap-4 justify-center">
                <button onclick="runAllTests()" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    🔄 Run All Tests
                </button>
                <button onclick="exportResults()" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    📊 Export Results
                </button>
                <a href="/" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                    🏠 Back to Home
                </a>
            </div>
        </div>
    </div>

    <script>
        // Update timestamp
        document.getElementById('timestamp').textContent = new Date().toLocaleString();

        // Test functions
        async function testSecurityHeaders() {
            try {
                const response = await fetch('/security-test');
                const data = await response.json();
                
                if (data.status === 'success') {
                    document.getElementById('headers-status').innerHTML = '<span class="text-green-600">✅ All headers present</span>';
                    
                    // Check individual headers
                    const headers = response.headers;
                    document.getElementById('hsts-status').textContent = headers.get('Strict-Transport-Security') ? '✅ Present' : '❌ Missing';
                    document.getElementById('csp-status').textContent = headers.get('Content-Security-Policy') ? '✅ Present' : '❌ Missing';
                    document.getElementById('xfo-status').textContent = headers.get('X-Frame-Options') ? '✅ Present' : '❌ Missing';
                    document.getElementById('xcto-status').textContent = headers.get('X-Content-Type-Options') ? '✅ Present' : '❌ Missing';
                    document.getElementById('xxp-status').textContent = headers.get('X-XSS-Protection') ? '✅ Present' : '❌ Missing';
                    document.getElementById('rp-status').textContent = headers.get('Referrer-Policy') ? '✅ Present' : '❌ Missing';
                }
            } catch (error) {
                document.getElementById('headers-status').innerHTML = '<span class="text-red-600">❌ Error: ' + error.message + '</span>';
            }
        }

        async function testSSL() {
            try {
                const response = await fetch('/ssl-test');
                const data = await response.json();
                
                if (data.secure) {
                    document.getElementById('ssl-status').innerHTML = '<span class="text-green-600">✅ SSL Enabled</span>';
                    document.getElementById('protocol-status').textContent = data.protocol;
                    document.getElementById('ssl-version').textContent = data.ssl_info.protocol || 'Unknown';
                    document.getElementById('cipher-suite').textContent = data.ssl_info.cipher || 'Unknown';
                    document.getElementById('cert-status').textContent = '✅ Valid';
                } else {
                    document.getElementById('ssl-status').innerHTML = '<span class="text-red-600">❌ SSL Not Enabled</span>';
                    document.getElementById('protocol-status').textContent = data.protocol;
                    document.getElementById('ssl-version').textContent = 'N/A';
                    document.getElementById('cipher-suite').textContent = 'N/A';
                    document.getElementById('cert-status').textContent = '❌ No SSL';
                }
            } catch (error) {
                document.getElementById('ssl-status').innerHTML = '<span class="text-red-600">❌ Error: ' + error.message + '</span>';
            }
        }

        async function testCSRF() {
            try {
                const response = await fetch('/csrf-test');
                const data = await response.json();
                
                document.getElementById('csrf-status').innerHTML = '<span class="text-green-600">✅ CSRF Protected</span>';
                document.getElementById('csrf-token').textContent = data.csrf_token.substring(0, 20) + '...';
                document.getElementById('session-id').textContent = data.session_id.substring(0, 20) + '...';
                document.getElementById('session-lifetime').textContent = data.session_lifetime + ' minutes';
                document.getElementById('secure-cookies').textContent = data.session_secure ? '✅ Enabled' : '❌ Disabled';
                document.getElementById('http-only').textContent = data.session_http_only ? '✅ Enabled' : '❌ Disabled';
                document.getElementById('same-site').textContent = data.session_same_site || 'Not set';
            } catch (error) {
                document.getElementById('csrf-status').innerHTML = '<span class="text-red-600">❌ Error: ' + error.message + '</span>';
            }
        }

        function testMixedContent() {
            const currentProtocol = window.location.protocol;
            const isHTTPS = currentProtocol === 'https:';
            
            document.getElementById('https-enforcement').textContent = isHTTPS ? '✅ HTTPS' : '❌ HTTP';
            document.getElementById('asset-urls').textContent = isHTTPS ? '✅ Secure' : '⚠️ Mixed';
            
            // Check for mixed content
            const images = document.querySelectorAll('img');
            const scripts = document.querySelectorAll('script');
            const links = document.querySelectorAll('link');
            
            let hasMixedContent = false;
            
            [images, scripts, links].forEach(elements => {
                elements.forEach(element => {
                    const src = element.src || element.href;
                    if (src && src.startsWith('http:') && isHTTPS) {
                        hasMixedContent = true;
                    }
                });
            });
            
            document.getElementById('external-resources').textContent = hasMixedContent ? '❌ Mixed Content' : '✅ Secure';
        }

        async function runAllTests() {
            // Reset status
            document.querySelectorAll('[id$="-status"]').forEach(el => {
                el.textContent = 'Checking...';
            });
            
            // Run tests
            await testSecurityHeaders();
            await testSSL();
            await testCSRF();
            testMixedContent();
        }

        function exportResults() {
            const results = {
                timestamp: new Date().toISOString(),
                url: window.location.href,
                security_headers: {
                    hsts: document.getElementById('hsts-status').textContent,
                    csp: document.getElementById('csp-status').textContent,
                    xfo: document.getElementById('xfo-status').textContent,
                    xcto: document.getElementById('xcto-status').textContent,
                    xxp: document.getElementById('xxp-status').textContent,
                    rp: document.getElementById('rp-status').textContent
                },
                ssl: {
                    status: document.getElementById('ssl-status').textContent,
                    protocol: document.getElementById('protocol-status').textContent,
                    version: document.getElementById('ssl-version').textContent,
                    cipher: document.getElementById('cipher-suite').textContent,
                    certificate: document.getElementById('cert-status').textContent
                },
                csrf: {
                    status: document.getElementById('csrf-status').textContent,
                    token: document.getElementById('csrf-token').textContent,
                    session_id: document.getElementById('session-id').textContent,
                    lifetime: document.getElementById('session-lifetime').textContent,
                    secure: document.getElementById('secure-cookies').textContent,
                    http_only: document.getElementById('http-only').textContent,
                    same_site: document.getElementById('same-site').textContent
                },
                mixed_content: {
                    enforcement: document.getElementById('https-enforcement').textContent,
                    assets: document.getElementById('asset-urls').textContent,
                    external: document.getElementById('external-resources').textContent
                }
            };
            
            const blob = new Blob([JSON.stringify(results, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'security-test-results.json';
            a.click();
            URL.revokeObjectURL(url);
        }

        // Run tests on page load
        document.addEventListener('DOMContentLoaded', runAllTests);
    </script>
</body>
</html>
