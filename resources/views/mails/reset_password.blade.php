<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 20px;
        }

        a {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007BFF;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        /* a:hover {
            background-color: #0056b3;
        } */
        .btn-common {
            color: #fff;
            background: #7DCCFF;
        }

        .btn-common:hover {
            color: #000000;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Reset Your Password</h1>
        <p>Click the link below to reset your password:</p>
        <a href="{{ url('reset-password/' . $token) }}" class="btn  btn-common">
            Reset Password
        </a>
    </div>
</body>

</html>
