<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Set Your Password</title>
</head>

<body style="margin:0; padding:0; background-color:#f4f4f4; font-family:Arial, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 0;">
    <tr>
        <td align="center">

            <table width="420" cellpadding="0" cellspacing="0"
                   style="background:#ffffff; padding:30px; border-radius:8px; text-align:center;">

                <!-- Logo -->
                <tr>
                    <td style="padding-bottom:20px;">
                        <img src="{{ url('logo.png') }}" alt="{{ config('app.name') }} Logo" 
                             style="max-width:150px; height:auto;">
                    </td>
                </tr>

                <!-- Heading -->
                <tr>
                    <td style="font-size:24px; font-weight:bold; color:#333333; padding-bottom:15px;">
                        Hello, {{ $user->name }}
                    </td>
                </tr>

                <!-- Message -->
                <tr>
                    <td style="font-size:16px; color:#333333; line-height:1.6; padding-bottom:20px;">
                        Your account has been successfully created. To set your password and access your account, click the button below.
                    </td>
                </tr>

                <!-- Button with token & email -->
                <tr>
                    <td align="center" style="padding-bottom:25px;">
                        <a href="{{ config('frontend.login_url') }}/reset-password?token={{ urlencode($token) }}&email={{ urlencode($user->email) }}"
                           style="background:#7DCCFF; color:#ffffff; text-decoration:none;
                                  padding:14px 28px; font-size:16px; border-radius:5px; display:inline-block;">
                            Set Your Password
                        </a>
                    </td>
                </tr>

                <!-- Security note -->
                <tr>
                    <td style="font-size:16px; color:#333333; line-height:1.6; padding-bottom:20px;">
                        If you did not request this, please ignore this email. Your account will remain secure.
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="font-size:14px; color:#777777; line-height:1.6;">
                        If the button doesn’t work, copy and paste this URL into your browser:
                        <br>
                        <span style="word-break:break-all;">
                            {{ config('frontend.login_url') }}/reset-password?token={{ urlencode($token) }}&email={{ urlencode($user->email) }}
                        </span>
                        <br><br>
                        Thank you,<br>
                        {{ config('app.name') }}
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
