<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رمز التحقق من البريد الإلكتروني</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            direction: rtl;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid #eee;
        }
        .content {
            padding: 20px 0;
            text-align: center;
        }
        .verification-code {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 4px;
            color: #2563eb;
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            display: inline-block;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>رمز التحقق من البريد الإلكتروني</h1>
        </div>
        <div class="content">
            <p>مرحباً {{ $name }}،</p>
            <p>لقد تلقينا طلباً للتحقق من بريدك الإلكتروني. يرجى استخدام رمز التحقق التالي لإكمال عملية التسجيل:</p>
            
            <div class="verification-code">{{ $code }}</div>
            
            <p>ينتهي هذا الرمز بعد 15 دقيقة من وقت الإرسال.</p>
            <p>إذا لم تكن أنت من قام بالتسجيل، يرجى تجاهل هذا البريد الإلكتروني.</p>
        </div>
        <div class="footer">
            <p>هذا بريد إلكتروني تلقائي، يرجى عدم الرد عليه.</p>
            <p>&copy; {{ date('Y') }} موقع حرفي. جميع الحقوق محفوظة.</p>
        </div>
    </div>
</body>
</html> 