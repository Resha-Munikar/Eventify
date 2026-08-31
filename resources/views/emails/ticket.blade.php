<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Event Ticket</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .ticket-container {
            max-width: 600px;
            margin: auto;
            background-color: #fff;
            border: 2px dashed #8D85EC;
            border-radius: 12px;
            padding: 20px;
            position: relative;
        }
        .ticket-header {
            text-align: center;
            border-bottom: 2px dashed #8D85EC;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .ticket-header h1 {
            margin: 0;
            color: #8D85EC;
        }
        .ticket-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .ticket-info div {
            width: 48%;
        }
        .ticket-info p {
            margin: 6px 0;
        }
        .ticket-footer {
            text-align: center;
            border-top: 2px dashed #8D85EC;
            padding-top: 10px;
            color: #555;
            font-size: 14px;
        }
        .qr-code {
            text-align: center;
            margin-top: 20px;
        }
        .qr-code img {
            width: 120px;
            height: 120px;
        }
        .highlight {
            font-weight: bold;
            color: #8D85EC;
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <div class="ticket-header">
            <h1>🎫 Event Ticket</h1>
            <p>Hello {{ $user->name }}</p>
        </div>

        <div class="ticket-info">
            <div>
                <p><span class="highlight">Event:</span> {{ $event->event_name }}</p>
                <p><span class="highlight">Venue:</span> {{ $event->venue }}</p>
                <p><span class="highlight">Date:</span> {{ \Carbon\Carbon::parse($event->event_date)->format('d M, Y') }}</p>
                @if(!empty($ticketType))
                <p><span class="highlight">Ticket Type:</span> {{ $ticketType->name }}</p>
                @endif
            </div>
            <div>
                <p><span class="highlight">Quantity:</span> {{ $tickets }}</p>
                @php
                    $unitPrice = $booking->price_per_ticket ?? $ticketType->price ?? $event->price ?? 0;
                    $total = $booking->total_amount ?? ($unitPrice * $tickets + 5.65);
                @endphp
                <p><span class="highlight">Price per ticket:</span> Rs {{ number_format($unitPrice, 2) }}</p>
                @if(isset($booking->service_charge) && $booking->service_charge > 0)
                <p><span class="highlight">Service Charge:</span> Rs {{ number_format($booking->service_charge, 2) }}</p>
                @endif
                <p><span class="highlight">Total Amount:</span> Rs {{ number_format($total, 2) }}</p>
            </div>
        </div>

        <div class="qr-code">
            <p>Scan for verification:</p>
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode('Event: '.$event->event_name.', Ticket: '.($ticketType->name ?? 'Standard').', User: '.$user->name) }}" alt="QR Code">
        </div>

        <div class="ticket-footer">
            <p>Please present this ticket at the event entrance.</p>
            <p>Enjoy the event!</p>
        </div>
    </div>
</body>
</html>
