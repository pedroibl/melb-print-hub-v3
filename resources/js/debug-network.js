// Network Request Debugging Script
// Add this to your browser console to monitor network requests

(function() {
    'use strict';
    
    console.log('🔍 Network Request Monitor Started');
    
    // Monitor all network requests
    const originalFetch = window.fetch;
    window.fetch = function(...args) {
        const url = args[0];
        if (typeof url === 'string' && url.includes('chrome-extension')) {
            console.error('🚨 Chrome Extension Request Detected:', url);
            console.trace('Request Stack Trace');
        }
        return originalFetch.apply(this, args);
    };
    
    // Monitor XMLHttpRequest
    const originalXHROpen = XMLHttpRequest.prototype.open;
    XMLHttpRequest.prototype.open = function(method, url, ...args) {
        if (typeof url === 'string' && url.includes('chrome-extension')) {
            console.error('🚨 Chrome Extension XHR Request:', url);
            console.trace('XHR Request Stack Trace');
        }
        return originalXHROpen.apply(this, [method, url, ...args]);
    };
    
    // Monitor script loading
    const originalCreateElement = document.createElement;
    document.createElement = function(tagName) {
        const element = originalCreateElement.call(this, tagName);
        if (tagName.toLowerCase() === 'script') {
            const originalSetAttribute = element.setAttribute;
            element.setAttribute = function(name, value) {
                if (name === 'src' && value.includes('chrome-extension')) {
                    console.error('🚨 Chrome Extension Script Loading:', value);
                    console.trace('Script Loading Stack Trace');
                }
                return originalSetAttribute.call(this, name, value);
            };
        }
        return element;
    };
    
    // Monitor iframe creation
    const originalCreateElementNS = document.createElementNS;
    document.createElementNS = function(namespaceURI, qualifiedName) {
        const element = originalCreateElementNS.call(this, namespaceURI, qualifiedName);
        if (qualifiedName.toLowerCase() === 'iframe') {
            const originalSetAttribute = element.setAttribute;
            element.setAttribute = function(name, value) {
                if (name === 'src' && value.includes('chrome-extension')) {
                    console.error('🚨 Chrome Extension Iframe Loading:', value);
                    console.trace('Iframe Loading Stack Trace');
                }
                return originalSetAttribute.call(this, name, value);
            };
        }
        return element;
    };
    
    console.log('✅ Network monitoring active. Check console for chrome-extension requests.');
})();
