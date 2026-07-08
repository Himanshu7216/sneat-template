<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
</head>

<body style="font-family: Arial, sans-serif;">

    <h2>Hello {{ $data['name'] }},</h2>

    <p>
        We received a request to reset your password.
    </p>

    <p>
        Click the button below to reset your password.
    </p>

    <p>
        <a href="{{ $data['link'] }}"
            style="
                background:#0d6efd;
                color:#fff;
                padding:12px 25px;
                text-decoration:none;
                border-radius:5px;
                display:inline-block;
            ">
            Reset Password
        </a>
    </p>

    <p>
        Or copy and paste this link into your browser:
    </p>

    <p>
        {{ $data['link'] }}
    </p>

    <br>

    <p>
        If you didn't request this password reset, you can safely ignore this email.
    </p>

    <p>
        Thanks,<br>
        {{-- {{ config('app.name') }} --}}
    </p>

</body>

</html>
