<!DOCTYPE html>
<html>
<head>
    <title>Payment Received</title>
</head>
<body>
<h1>Payment Received</h1>
<p>Dear {{ $transaction->user->name }},</p>
<p>We have received your payment of ${{ $transaction->price }} for your subscription.</p>
<p>Your subscription has been successfully renewed.</p>
<p>Thank you for your payment!</p>
</body>
</html>
