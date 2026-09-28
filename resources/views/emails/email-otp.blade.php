<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Eventify Verification Code</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f7f9fc;
            color: #2d3748;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .wrapper {
            width: 100%;
            background-color: #f7f9fc;
            padding: 40px 15px;
            box-sizing: border-box;
        }
        .container {
            max-width: 540px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border: 1px solid #e9ecef;
        }
        .header {
            background: linear-gradient(135deg, #8D85EC, #766ee6);
            padding: 30px 24px;
            text-align: center;
            color: #ffffff;
        }
        .brand-title {
            margin: 0;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .brand-subtitle {
            margin: 6px 0 0 0;
            font-size: 13px;
            opacity: 0.9;
        }
        .content {
            padding: 32px 28px;
        }
        .greeting {
            font-size: 16px;
            font-weight: 600;
            color: #1a202c;
            margin-top: 0;
            margin-bottom: 12px;
        }
        .instructions {
            font-size: 14.5px;
            color: #4a5568;
            margin-bottom: 24px;
        }
        .otp-container {
            text-align: center;
            background: #f4f2ff;
            border: 2px dashed #8D85EC;
            border-radius: 12px;
            padding: 20px;
            margin: 24px 0;
        }
        .otp-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #6b63db;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .otp-code {
            font-size: 36px;
            font-weight: 800;
            letter-spacing: 8px;
            color: #8D85EC;
            margin: 0;
            font-family: 'Courier New', Courier, monospace;
        }
        .expiry-note {
            font-size: 13px;
            color: #718096;
            margin-top: 8px;
            margin-bottom: 0;
        }
        .security-notice {
            background-color: #fffaf0;
            border-left: 4px solid #dd6b20;
            padding: 12px 16px;
            border-radius: 6px;
            font-size: 13px;
            color: #744210;
            margin-top: 24px;
        }
        .footer {
            border-top: 1px solid #edf2f7;
            padding: 20px 24px;
            text-align: center;
            font-size: 12px;
            color: #a0aec0;
            background-color: #fafbfc;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1 class="brand-title">Eventify</h1>
                <p class="brand-subtitle">Email Verification</p>
            </div>
            <div class="content">
                <p class="greeting">Hello {{ $userName }},</p>
                <p class="instructions">Thank you for registering with Eventify! Please use the 6-digit verification code below to complete your registration and verify your email address:</p>
                
                <div class="otp-container">
                    <div class="otp-label">Your Verification Code</div>
                    <div class="otp-code">{{ $otp }}</div>
                    <p class="expiry-note">This code will expire in <strong>5 minutes</strong>.</p>
                </div>

                <div class="security-notice">
                    <strong>Security Notice:</strong> Never share this verification code with anyone. If you did not create an Eventify account, you can safely ignore this email.
                </div>
            </div>
            <div class="footer">
                <p>&copy; {{ date('Y') }} Eventify. All rights reserved.</p>
                <p>Bringing events and memorable experiences together.</p>
            </div>
        </div>
    </div>
</body>
</html>
