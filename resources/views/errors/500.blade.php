<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Error</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f8f9fa;
            color: #343a40;
            font-family: Arial, sans-serif;
        }
        .container {
            text-align: center;
        }
        .error-code {
            font-size: 100px;
            font-weight: bold;
        }
        .error-message {
            font-size: 24px;
            margin-top: 10px;
        }
        .error-description {
            font-size: 18px;
            margin-top: 10px;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-code">500</div>
        <div class="error-message">Server Error</div>
        <div class="error-description">
            Oops! Something went wrong on our end. Please try again later or contact support if the problem persists.
        </div>
        <div class="mt-4">
            <a href="{{ url('/') }}">Return to Home</a>
        </div>
    </div>
</body>
</html>
