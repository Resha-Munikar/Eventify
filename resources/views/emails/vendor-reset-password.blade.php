<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Eventify Password</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f6f8fd;
            color: #2d3748;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            background-color: #f6f8fd;
            padding: 40px 15px;
            box-sizing: border-box;
        }
        .container {
            max-width: 560px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(105, 97, 226, 0.08);
            border: 1px solid #eef0f7;
        }
        .header {
            background: linear-gradient(135deg, #6961e2 0%, #8D85EC 100%);
            padding: 36px 28px;
            text-align: center;
            color: #ffffff;
        }
        .header-icon {
            display: inline-block;
            width: 54px;
            height: 54px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            line-height: 54px;
            font-size: 26px;
            margin-bottom: 12px;
        }
        .brand-title {
            margin: 0;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #ffffff;
        }
        .brand-subtitle {
            margin: 6px 0 0 0;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
        }
        .content {
            padding: 36px 32px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 700;
            color: #1a202c;
            margin-top: 0;
            margin-bottom: 14px;
        }
        .text {
            font-size: 15px;
            color: #4a5568;
            margin: 0 0 18px 0;
            line-height: 1.65;
        }
        .btn-container {
            text-align: center;
            margin: 32px 0;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #6961e2 0%, #7b73e8 100%);
            color: #ffffff !important;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            padding: 14px 36px;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(105, 97, 226, 0.35);
            letter-spacing: 0.2px;
        }
        .info-card {
            background-color: #f8f9fe;
            border: 1px solid #e8eaff;
            border-radius: 12px;
            padding: 16px 18px;
            margin: 24px 0;
        }
        .info-card-item {
            display: flex;
            align-items: flex-start;
            font-size: 13.5px;
            color: #5a6578;
            margin-bottom: 8px;
            line-height: 1.5;
        }
        .info-card-item:last-child {
            margin-bottom: 0;
        }
        .info-card-item strong {
            color: #2d3748;
        }
        .fallback-link {
            font-size: 12.5px;
            color: #718096;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #edf2f7;
            word-break: break-all;
            line-height: 1.5;
        }
        .fallback-link a {
            color: #6961e2;
            text-decoration: underline;
        }
        .footer {
            border-top: 1px solid #edf2f7;
            padding: 24px 32px;
            text-align: center;
            font-size: 12px;
            color: #a0aec0;
            background-color: #fafbfc;
        }
        .footer p {
            margin: 4px 0;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Header -->
            <div class="header">
                <div class="header-icon">🔒</div>
                <h1 class="brand-title">Eventify</h1>
                <p class="brand-subtitle">Password Reset Request</p>
            </div>

            <!-- Content -->
            <div class="content">
                <p class="greeting">Hello {{ $userName ?? 'there' }},</p>
                <p class="text">
                    We received a request to reset the password for your Eventify account associated with <strong>{{ $email ?? 'your email' }}</strong>.
                </p>
                <p class="text">
                    Click the button below to choose a new password:
                </p>

                <!-- Action Button -->
                <div class="btn-container">
                    <a href="{{ $resetLink }}" target="_blank" class="btn">Reset Password</a>
                </div>

                <!-- Info / Security Box -->
                <div class="info-card">
                    <div class="info-card-item">
                        <span>⏳ <strong>Expiry:</strong> This reset link is valid for 60 minutes.</span>
                    </div>
                    <div class="info-card-item" style="margin-top: 8px;">
                        <span>🛡️ <strong>Security Tip:</strong> If you did not request a password reset, you can safely ignore this email. Your password will not change and your account remains secure.</span>
                    </div>
                </div>

                <!-- Fallback URL -->
                <div class="fallback-link">
                    <p style="margin: 0 0 6px 0; font-weight: 600; color: #4a5568;">Having trouble with the button?</p>
                    <p style="margin: 0;">Copy and paste this URL into your browser:</p>
                    <a href="{{ $resetLink }}" target="_blank">{{ $resetLink }}</a>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>&copy; {{ date('Y') }} Eventify. All rights reserved.</p>
                <p>Bringing events, venues, and experiences together.</p>
            </div>
        </div>
    </div>
</body>
</html>
