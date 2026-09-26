<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventify KYC Verification Approved</title>
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
            background: linear-gradient(135deg, #8D85EC, #766ee6);
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
        .status-badge-box {
            text-align: center;
            background: #f0fdf4;
            border: 2px solid #86efac;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0 24px 0;
        }
        .status-badge {
            display: inline-block;
            background-color: #16a34a;
            color: #ffffff;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 6px 16px;
            border-radius: 9999px;
            margin-bottom: 8px;
        }
        .status-text {
            font-size: 15px;
            font-weight: 600;
            color: #15803d;
            margin: 4px 0 0 0;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            font-size: 14px;
        }
        .info-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #edf2f7;
        }
        .info-label {
            color: #718096;
            font-weight: 600;
            width: 40%;
        }
        .info-value {
            color: #1a202c;
            font-weight: 600;
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
                <p class="brand-subtitle">Vendor KYC Verification Approved</p>
            </div>
            <div class="content">
                <p class="greeting">Hello {{ $user->name }},</p>
                <p>Great news! Your Vendor KYC verification for <strong>{{ $kyc->business_name }}</strong> has been reviewed and <strong style="color: #16a34a;">approved</strong> by our team.</p>
                
                <div class="status-badge-box">
                    <span class="status-badge">Verified Vendor</span>
                    <p class="status-text">Your organizer account is now fully active!</p>
                </div>

                <table class="info-table">
                    <tr>
                        <td class="info-label">Business / Org Name:</td>
                        <td class="info-value">{{ $kyc->business_name }}</td>
                    </tr>
                    @if($kyc->pan_vat_number)
                    <tr>
                        <td class="info-label">PAN / VAT Number:</td>
                        <td class="info-value">{{ $kyc->pan_vat_number }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="info-label">Document Type:</td>
                        <td class="info-value">{{ $kyc->document_type }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Approved On:</td>
                        <td class="info-value">{{ optional($kyc->approved_at)->format('M d, Y h:i A') ?? date('M d, Y') }}</td>
                    </tr>
                </table>

                <p>You can now create and publish events, list venues, manage ticket bookings, and connect with attendees on Eventify.</p>

                <div class="btn-container">
                    <a href="{{ route('vendor.dashboard') }}" class="btn">Go to Vendor Dashboard</a>
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
