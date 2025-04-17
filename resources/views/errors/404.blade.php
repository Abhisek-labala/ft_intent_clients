<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #e9ecef;
            color: #495057;
            font-family: Arial, sans-serif;
        }
        .container {
            text-align: center;
        }
        .error-code {
            font-size: 120px;
            font-weight: bold;
            color: #dc3545;
        }
        .error-message {
            font-size: 24px;
            margin-top: 10px;
            color: #343a40;
        }
        .error-description {
            font-size: 18px;
            margin-top: 10px;
            color: #6c757d;
        }
        a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        .search-form {
            margin-top: 20px;
        }
        .search-input {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            width: 300px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-code">404</div>
        <div class="error-message">Page Not Found</div>
        <div class="error-description">
            Sorry, the page you are looking for might have been moved or deleted. Please check the URL or return to the homepage.
        </div>
        <div class="mt-4">
            <a href="{{ url('/') }}">Return to Home</a>
        </div>
         </div>
</body>
</html>
