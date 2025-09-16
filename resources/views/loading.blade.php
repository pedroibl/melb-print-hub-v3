<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing - Melbourne Print Hub</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }

        .loading-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 3rem;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 90%;
            animation: fadeInUp 0.8s ease-out;
        }

        .logo {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .loading-title {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .loading-message {
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .spinner-container {
            margin: 2rem 0;
            position: relative;
        }

        .spinner {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            position: relative;
        }

        .spinner svg {
            width: 100%;
            height: 100%;
            animation: rotate 2s linear infinite;
        }

        .spinner circle {
            fill: none;
            stroke: #667eea;
            stroke-width: 4;
            stroke-linecap: round;
            stroke-dasharray: 150, 200;
            stroke-dashoffset: 0;
            animation: dash 1.5s ease-in-out infinite;
        }

        .progress-bar {
            width: 100%;
            height: 6px;
            background: #e0e0e0;
            border-radius: 3px;
            overflow: hidden;
            margin: 1rem 0;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 3px;
            animation: progress 2s ease-in-out infinite;
        }

        .status-text {
            font-size: 0.9rem;
            color: #888;
            margin-top: 1rem;
            font-style: italic;
        }

        .contact-info {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #eee;
        }

        .contact-info p {
            color: #666;
            font-size: 0.9rem;
            margin: 0.5rem 0;
        }

        .contact-info strong {
            color: #333;
        }

        @keyframes rotate {
            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes dash {
            0% {
                stroke-dasharray: 1, 200;
                stroke-dashoffset: 0;
            }
            50% {
                stroke-dasharray: 89, 200;
                stroke-dashoffset: -35;
            }
            100% {
                stroke-dasharray: 89, 200;
                stroke-dashoffset: -124;
            }
        }

        @keyframes progress {
            0% {
                width: 0%;
            }
            50% {
                width: 70%;
            }
            100% {
                width: 100%;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }

        @media (max-width: 768px) {
            .loading-container {
                padding: 2rem;
                margin: 1rem;
            }
            
            .loading-title {
                font-size: 1.3rem;
            }
            
            .spinner {
                width: 60px;
                height: 60px;
            }
        }
    </style>
</head>
<body>
    <div class="loading-container">
        <div class="logo pulse">🖨️ Melbourne Print Hub</div>
        
        <h1 class="loading-title">Processing Your Request</h1>
        
        <p class="loading-message">
            Please wait while your submission is loading. We're processing your information and will redirect you shortly.
        </p>

        <div class="spinner-container">
            <div class="spinner">
                <svg viewBox="0 0 50 50">
                    <circle cx="25" cy="25" r="20" stroke="#667eea" stroke-width="4" fill="none"></circle>
                </svg>
            </div>
        </div>

        <div class="progress-bar">
            <div class="progress-fill"></div>
        </div>

        <div class="status-text">
            <span id="status">Preparing your submission...</span>
        </div>

        <div class="contact-info">
            <p><strong>Need immediate assistance?</strong></p>
            <p><strong>Phone:</strong> 0449 598 440</p>
            <p><strong>Email:</strong> info@melbourneprinthub.com.au</p>
            <p><strong>Hours:</strong> Monday to Friday, 08:00 AM to 06:00 PM</p>
        </div>
    </div>

    <script>
        // Simulate processing steps
        const statusMessages = [
            'Preparing your submission...',
            'Validating information...',
            'Processing request...',
            'Sending confirmation...',
            'Almost done...'
        ];

        let currentStep = 0;
        const statusElement = document.getElementById('status');

        function updateStatus() {
            if (currentStep < statusMessages.length) {
                statusElement.textContent = statusMessages[currentStep];
                currentStep++;
            } else {
                currentStep = 0;
            }
        }

        // Update status every 1.5 seconds
        setInterval(updateStatus, 1500);

        // Auto-redirect after 5 seconds (you can adjust this)
        setTimeout(() => {
            window.location.href = '/thanks';
        }, 5000);
    </script>
</body>
</html>
