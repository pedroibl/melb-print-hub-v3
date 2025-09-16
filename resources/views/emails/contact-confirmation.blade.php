<!DOCTYPE html>
<html>
<head>
    <title>Message Confirmation</title>
</head>
<body>
    <h2>Thank you for contacting us!</h2>
    
    <p>Hi {{ $contactMessage->name }},</p>
    
    <p>We've received your message and will get back to you within 24 hours.</p>
    
    <h3>Your Message Summary:</h3>
    <p><strong>Message:</strong> {{ $contactMessage->message }}</p>
    
    <p>If you need immediate assistance, please call us at <strong>0449 598 440</strong>.</p>
    
    <p>Best regards,<br>
    The Melbourne Print Hub Team</p>
</body>
</html>
