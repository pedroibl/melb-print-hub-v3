import React from 'react';

const SimpleTestButton = () => {
    console.log('SimpleTestButton rendering');
    return (
        <div className="bg-blue-500 text-white p-4 rounded">
            <p>Simple Test Button</p>
            <p>This should be visible</p>
        </div>
    );
};

export default SimpleTestButton;
