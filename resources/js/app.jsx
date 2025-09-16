import './bootstrap';
import '../css/app.css';

import React from 'react';
import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.jsx`, import.meta.glob('./Pages/**/*.jsx')),
    setup({ el, App, props }) {
        // Ensure React is available before creating root
        if (typeof React === 'undefined') {
            console.error('React is not available');
            return;
        }
        
        const root = createRoot(el);
        
        // Add error boundary
        const ErrorBoundary = ({ children }) => {
            try {
                return children;
            } catch (error) {
                console.error('React Error:', error);
                return <div>Something went wrong. Please refresh the page.</div>;
            }
        };

        root.render(
            <ErrorBoundary>
                <App {...props} />
            </ErrorBoundary>
        );
    },
    progress: {
        color: '#4B5563',
    },
});
