<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventify KYC Verification Update</title>
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
            max-width: 560px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border: 1px solid #e9ecef;
        }
        .header {
            background: linear-gradient(135deg, #e11d48, #be123c);
            padding: 32px 24px;
            text-align: center;
            color: #ffffff;
        }
        .brand-title {
            margin: 0;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .brand-subtitle {
            margin: 6px 0 0 0;
            font-size: 14px;
            opacity: 0.95;
        }
        .content {
            padding: 32px 28px;
        }
        .greeting {
            font-size: 17px;
            font-weight: 600;
            color: #1a202c;
            margin-top: 0;
            margin-bottom: 12px;
        }
        .rejection-box {
            background-color: #fff1f2;
            border-left: 4px solid #e11d48;
            border-radius: 8px;
            padding: 16px 20px;
            margin: 20px 0 24px 0;
        }
        .rejection-title {
            color: #9f1239;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 6px 0;
        }
        .rejection-reason {
            color: #881337;
            font-size: 15px;
            margin: 0;
            white-space: pre-line;
        }
        .instructions {
            font-size: 14.5px;
            color: #4a5568;
            margin-bottom: 24px;
        }
        .btn-container {
            text-align: center;
            margin: 30px 0 20px 0;
        }
        .btn {
            display: inline-block;
            background-color: #8D85EC;
            color: #ffffff !important;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            padding: 14px 32px;
            border-radius: 50px;
            box-shadow: 0 4px 12px rgba(141, 133, 236, 0.35);
        }
        .btn:hover {
            background-color: #7870dc;
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
                <p class="brand-subtitle">Vendor KYC Verification Status</p>
            </div>
            <div class="content">
                <p class="greeting">Hello {{ $user->name }},</p>
                <p class="instructions">Thank you for submitting your Vendor KYC documents for <strong>{{ $kyc->business_name }}</strong>. After reviewing your submission, our verification team was unable to approve your application due to the following reason:</p>
                
                <div class="rejection-box">
                    <div class="rejection-title">Feedback & Reason:</div>
                    <p class="rejection-reason">{{ $reason }}</p>
                </div>

                <p class="instructions">Don't worry! You can easily update your information or upload clearer, valid documents directly from your vendor dashboard.</p>

                <div class="btn-container">
                    <a href="{{ route('vendor.kyc.resubmit') }}" class="btn">Resubmit KYC Documents</a>
                </div>
            </div>
            <div class="footer">
                <p>&copy; {{ date('Y') }} Eventify. All rights reserved.</p>
                <p>Empowering organizers and creating memorable experiences.</p>
            </div>
        </div>
    </div>
</body>
</html>
