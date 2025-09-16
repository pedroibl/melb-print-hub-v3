<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Form - Melbourne Print Hub</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background: #667eea;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background: #5a6fd8;
        }
        .success {
            color: green;
            font-weight: bold;
        }
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>🧪 Test Form</h1>
        <p>Use this form to test the loading page flow:</p>
        
        <form action="/contact" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="Test User" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="test@example.com" required>
            </div>
            
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="4" required>Testing the loading page flow</textarea>
            </div>
            
            <button type="submit">Submit Test</button>
        </form>
        
        <hr style="margin: 30px 0;">
        
        <h2>Test Results:</h2>
        @if(session('success'))
            <p class="success">{{ session('success') }}</p>
        @endif
        
        @if(session('error'))
            <p class="error">{{ session('error') }}</p>
        @endif
        
        <p><strong>Expected Flow:</strong></p>
        <ol>
            <li>Submit form → Redirect to /loading</li>
            <li>Loading page shows for 5 seconds</li>
            <li>Auto-redirect to /thanks</li>
        </ol>
        
        <p><a href="/loading">View Loading Page Directly</a></p>
        <p><a href="/thanks">View Thanks Page Directly</a></p>
    </div>
</body>
</html>
