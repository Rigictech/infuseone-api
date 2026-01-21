<!DOCTYPE html>
<html>

<head>
    <title>Your Account is Created</title>
    <style>
        .btn {
            display: inline-block;
            font-weight: 400;
            color: #212529;
            text-align: center;
            vertical-align: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-color: transparent;
            border: 1px solid transparent;
            padding: .375rem .75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: .25rem;
            transition: color .15s ease-in-out,
                background-color .15s ease-in-out,
                border-color .15s ease-in-out,
                box-shadow .15s ease-in-out;
        }

        .btn-common {
            color: #fff;
            background: #7DCCFF;
            text-decoration: none;
        }

        .btn-common:hover {
            color: #000000;
        }
    </style>
</head>

<body>
    <h1>Hello, {{ $user->name }}</h1>
    <p>Your account has been successfully created. You can now log in to your account.</p>
    <p>Please find the login details  : 
    <ul>    
    <li>Email : {{$user->email}}</li>
  
</ul>
    </p>
    <a class="btn btn-common" href={{ env('FRONT_REDIRECTION_MAIN_LOGIN_URL') }}>Login</a>
    <p>Thank you!</p>
</body>

</html>
