<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رمز التحقق</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f9f9f9;
            border-radius: 5px;
            padding: 20px;
            margin-top: 20px;
        }
        .otp {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            padding: 10px;
            background-color: #e9ecef;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #6c757d;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>مرحباً بك في نظام تسجيل الحرفيين</h2>
        
        <p>شكراً لتسجيلك كحرفي في نظامنا. لإكمال عملية التسجيل، يرجى استخدام رمز التحقق التالي:</p>
        
        <div class="otp"><?php echo e($otp); ?></div>
        
        <p>ينتهي هذا الرمز خلال 10 دقائق.</p>
        
        <p>إذا لم تطلب هذا الرمز، يرجى تجاهل هذا البريد الإلكتروني.</p>
        
        <div class="footer">
            <p>هذا البريد الإلكتروني تم إرساله تلقائياً، يرجى عدم الرد عليه.</p>
        </div>
    </div>
</body>
</html> <?php /**PATH C:\laragon\www\hirafi\resources\views/emails/otp.blade.php ENDPATH**/ ?>