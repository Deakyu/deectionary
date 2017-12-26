<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Deectionary</title>
</head>
<body style="background: #f3f3f3; font-family: Arial;">
    <div class="email" style="background: white;
            width: 70%;
            margin: 36px auto;
            padding: 18px;
            text-align: center;">
        <h1 class="email__title" style="margin: 18px 0;
            padding-bottom: 18px;
            border-bottom: 1px solid rgb(75, 181, 67);
            color: rgb(75, 181, 67);">
            Deectionary
        </h1>
        <h2 class="email__welcome" style="margin: 0 0 18px;">
            Welcome! you are almost there
        </h2>
        <h3 class="email__verify" style="margin: 0 0 18px;">
            To verify email, <a href="{{route('sendEmailDone', ["email" => $user->email, "verifyToken" => $user->verifyToken])}}">click here</a>
        </h3>
    </div>
</body>
</html>
