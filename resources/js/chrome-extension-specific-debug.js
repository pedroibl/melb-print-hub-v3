// Chrome Extension Specific Debug Script
// Target Extension ID: 6bb7751f-ce3e-4b1b-b329-6d11d6540cf9
// Target File: /assets/index.tsx-8992ba42.js

(function() {
    'use strict';
    
    const TARGET_EXTENSION_ID = '6bb7751f-ce3e-4b1b-b329-6d11d6540cf9';
    const TARGET_FILE = '/assets/index.tsx-8992ba42.js';
    
    console.log('🔍 Chrome Extension Debug Script Started');
    console.log('🎯 Looking for extension ID:', TARGET_EXTENSION_ID);
    console.log('📁 Looking for file:', TARGET_FILE);
    
    // Monitor dynamic imports specifically
    const originalImport = window.import;
    if (originalImport) {
        window.import = function(modulePath) {
            console.log('📦 Dynamic import attempted:', modulePath);
            
            if (typeof modulePath === 'string') {
                if (modulePath.includes(TARGET_EXTENSION_ID)) {
                    console.error('🚨 TARGET EXTENSION DYNAMIC IMPORT DETECTED!');
                    console.error('Extension ID:', TARGET_EXTENSION_ID);
                    console.error('Module Path:', modulePath);
                    console.trace('Dynamic Import Stack Trace');
                    
                    // Try to identify the source
                    const stack = new Error().stack;
                    console.error('Full Stack Trace:', stack);
                }
                
                if (modulePath.includes('chrome-extension')) {
                    console.warn('⚠️ Other Chrome Extension Import:', modulePath);
                }
            }
            
            return originalImport.call(this, modulePath);
        };
    }
    
    // Monitor fetch requests for the specific extension
    const originalFetch = window.fetch;
    window.fetch = function(...args) {
        const url = args[0];
        if (typeof url === 'string') {
            if (url.includes(TARGET_EXTENSION_ID)) {
                console.error('🚨 TARGET EXTENSION FETCH REQUEST DETECTED!');
                console.error('URL:', url);
                console.trace('Fetch Request Stack Trace');
            }
            
            if (url.includes('chrome-extension')) {
                console.warn('⚠️ Chrome Extension Fetch Request:', url);
            }
        }
        return originalFetch.apply(this, args);
    };
    
    // Monitor script loading
    const originalCreateElement = document.createElement;
    document.createElement = function(tagName) {
        const element = originalCreateElement.call(this, tagName);
        if (tagName.toLowerCase() === 'script') {
            const originalSetAttribute = element.setAttribute;
            element.setAttribute = function(name, value) {
                if (name === 'src' && typeof value === 'string') {
                    if (value.includes(TARGET_EXTENSION_ID)) {
                        console.error('🚨 TARGET EXTENSION SCRIPT LOADING DETECTED!');
                        console.error('Script Src:', value);
                        console.trace('Script Loading Stack Trace');
                    }
                    
                    if (value.includes('chrome-extension')) {
                        console.warn('⚠️ Chrome Extension Script Loading:', value);
                    }
                }
                return originalSetAttribute.call(this, name, value);
            };
        }
        return element;
    };
    
    // Monitor error events
    window.addEventListener('error', function(event) {
        if (event.message && event.message.includes(TARGET_EXTENSION_ID)) {
            console.error('🚨 TARGET EXTENSION ERROR EVENT DETECTED!');
            console.error('Error Message:', event.message);
            console.error('Error File:', event.filename);
            console.error('Error Line:', event.lineno);
            console.error('Error Column:', event.colno);
        }
    });
    
    // Monitor unhandled promise rejections
    window.addEventListener('unhandledrejection', function(event) {
        if (event.reason && event.reason.toString().includes(TARGET_EXTENSION_ID)) {
            console.error('🚨 TARGET EXTENSION PROMISE REJECTION DETECTED!');
            console.error('Promise Reason:', event.reason);
        }
    });
    
    // Check for existing scripts with the target extension
    function checkExistingScripts() {
        const scripts = document.querySelectorAll('script[src]');
        console.log('📋 Checking existing scripts for target extension...');
        
        scripts.forEach(script => {
            if (script.src.includes(TARGET_EXTENSION_ID)) {
                console.error('🚨 TARGET EXTENSION SCRIPT ALREADY LOADED!');
                console.error('Script Src:', script.src);
            }
        });
    }
    
    // Run initial check
    checkExistingScripts();
    
    // Check again after page load
    window.addEventListener('load', checkExistingScripts);
    
    // Add helper function to window
    window.chromeExtensionDebug = {
        targetExtensionId: TARGET_EXTENSION_ID,
        targetFile: TARGET_FILE,
        checkExistingScripts: checkExistingScripts,
        info: 'Access debug info via: window.chromeExtensionDebug'
    };
    
    console.log('✅ Chrome Extension Debug Script Active');
    console.log('💡 Access debug info via: window.chromeExtensionDebug');
    
})();
