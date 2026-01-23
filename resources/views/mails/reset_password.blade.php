<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>

<body style="margin:0; padding:0; background-color:#f4f4f4; font-family:Arial, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 0;">
    <tr>
        <td align="center">

            <table width="420" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:8px; padding:30px; box-shadow:0 2px 10px rgba(0,0,0,0.08);">

                <!-- Logo -->
                <tr>
                    <td align="center" style="padding-bottom:20px;">
                        <img src="{{ url('logo.png') }}"
                             alt="{{ config('app.name') }} Logo"
                             style="max-width:150px; height:auto;">
                    </td>
                </tr>

                <!-- Heading -->
                <tr>
                    <td align="center"
                        style="font-size:24px; font-weight:bold; color:#333333; padding-bottom:15px;">
                        Reset Your Password
                    </td>
                </tr>

                <!-- Main Text -->
                <tr>
                    <td style="font-size:16px; color:#333333; line-height:1.6; padding-bottom:15px;">
                        We received a request to reset the password for your
                        <strong>{{ config('app.name') }}</strong> account.
                    </td>
                </tr>

                <tr>
                    <td style="font-size:16px; color:#333333; line-height:1.6; padding-bottom:25px;">
                        Click the button below to choose a new password. This link
                        will expire after a limited time for your security.
                    </td>
                </tr>

                <!-- Button -->
                <tr>
                    <td align="center" style="padding-bottom:25px;">
                        <a href="{{ config('app.frontend_login_url') }}/reset-password?token={{ $token }}"
                           style="background:#7DCCFF; color:#ffffff; text-decoration:none;
                                  padding:14px 28px; font-size:17px;
                                  border-radius:5px; display:inline-block;">
                            Reset Password
                        </a>
                    </td>
                </tr>

                <!-- Warning -->
                <tr>
                    <td style="font-size:16px; color:#333333; line-height:1.6; padding-bottom:20px;">
                        If you did not request a password reset, please ignore this email.
                        Your password will remain unchanged.
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="font-size:14px; color:#777777; line-height:1.6;">
                        If you’re having trouble clicking the button, copy and paste
                        the URL below into your browser:
                        <br><br>
                        <span style="word-break:break-all;">
                            {{ rtrim(config('app.frontend_login_url'), '/') }}/reset-password?token={{ urlencode($token) }}
                        </span>
                        <br><br>
                        © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
