<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Include CSRF token -->
    <title>FlowTransact</title>

    <link rel="icon" type="image/png" href="{{asset('uploads/Logo/logo_new.png')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/tabicon.png') }}" />
    
    <!-- FONT LINKS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- BOOTSTRAP CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Include SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --background: #1a1a2e;
            --color: #ffffff;
            --primary-color: #0f3460;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            height: 100vh;
            padding: 0;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: var(--background);
            color: var(--color);
            letter-spacing: 1px;
            transition: background 0.2s ease;
        }

        a {
            text-decoration: none;
            color: var(--color);
        }

        h1 {
            font-size: 2.5rem;
            text-align: center;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            position: relative;
            width: 22.2rem;
        }

        .form-container {
            border: 1px solid hsla(0, 0%, 65%, 0.158);
            box-shadow: 0 0 36px 1px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            backdrop-filter: blur(20px);
            z-index: 99;
            padding: 2rem;
        }

        .form-container form input {
            display: block;
            padding: 14.5px;
            width: 100%;
            margin: 1rem 0;
            color: var(--color);
            outline: none;
            background-color: #9191911f;
            border: none;
            border-radius: 5px;
            font-weight: 500;
            font-size: 15px;
            backdrop-filter: blur(15px);
        }

        .form-container form input:focus {
            box-shadow: 0 0 16px 1px rgba(0, 0, 0, 0.2);
        }

        .form-container form button {
            background-color: var(--primary-color);
            color: var(--color);
            padding: 13px;
            border-radius: 5px;
            outline: none;
            font-size: 18px;
            font-weight: bold;
            width: 100%;
            cursor: pointer;
            margin: 1rem 0;
            border: none;
            transition: all 0.1s ease-in-out;
        }

        .form-container form button:hover {
            box-shadow: 0 0 10px 1px rgba(0, 0, 0, 0.15);
            transform: scale(1.02);
        }

        .circle {
            width: 8rem;
            height: 8rem;
            background: var(--primary-color);
            border-radius: 50%;
            position: absolute;
        }

        .circle-one {
            top: 0;
            left: 0;
            z-index: -1;
            transform: translate(-45%, -45%);
        }

        .circle-two {
            bottom: 0;
            right: 0;
            z-index: -1;
            transform: translate(45%, 45%);
        }

        .register-forget {
            margin: 1rem 0;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
        }

        .opacity {
            opacity: 0.6;
        }

        .theme-btn-container {
            position: absolute;
            left: 0;
            bottom: 2rem;
        }

        .theme-btn {
            cursor: pointer;
            transition: all 0.3s ease-in;
        }

        .theme-btn:hover {
            width: 40px !important;
        }

        #clientlogo {
            height: 150px;
            width: 150px;
            margin-left: auto;
            margin-right: auto;
            display: block;
        }
        #copyApiKey {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 4px;
    cursor: pointer;
}

#copyApiKey:hover {
    background-color: #0056b3;
}

    </style>
</head>
<body>
<section class="container">
    <div class="login-container" id="registerContainer">
        <img src="{{ asset('uploads/Logo/logo_new.png') }}" alt="Logo" id="clientlogo">
        <div class="form-container">
            <h1 class="opacity">REGISTER</h1>
            <form id="clientRegister" enctype="multipart/form-data" action="{{ route('clientregister') }}" method="POST">
                @csrf
                <input type="text" name="name" id="name" placeholder="Full Name" required aria-label="Full Name" />
                <input type="email" name="email" id="registerEmail" placeholder="Email Id" required aria-label="Email Id" />
                <input type="password" name="password" id="registerPassword" placeholder="Password" required aria-label="Password" />
                <button class="opacity" type="submit">REGISTER</button>
            </form>
            <div class="register-forget">
                <a href="{{route('clientlogin')}}" id="showLoginForm" class="opacity">Back to Login</a>
            </div>
        </div>
        <div class="circle circle-one"></div>
    </div>

    <div class="theme-btn-container"></div>
</section>


<script>
    const themes = [
        { background: "#1A1A2E", color: "#FFFFFF", primaryColor: "#0F3460" },
        { background: "#192A51", color: "#FFFFFF", primaryColor: "#967AA1" },
        { background: "#231F20", color: "#FFF", primaryColor: "#BB4430" }
    ];

    const setTheme = (theme) => {
        const root = document.querySelector(":root");
        root.style.setProperty("--background", theme.background);
        root.style.setProperty("--color", theme.color);
        root.style.setProperty("--primary-color", theme.primaryColor);
    };

    const displayThemeButtons = () => {
        const btnContainer = document.querySelector(".theme-btn-container");
        themes.forEach((theme) => {
            const div = document.createElement("div");
            div.className = "theme-btn";
            div.style.cssText = `background: ${theme.background}; width: 25px; height: 25px`;
            btnContainer.appendChild(div);
            div.addEventListener("click", () => setTheme(theme));
        });
    };

    displayThemeButtons();

    document.getElementById('clientRegister').addEventListener('submit', function (event) {
     event.preventDefault();
     const form = this;
     const formData = new FormData(form);
     const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

     fetch(form.action, {
         method: form.method,
         body: formData,
         headers: {
             'Accept': 'application/json',
             'X-CSRF-TOKEN': csrfToken
         }
     })
     .then(response => response.json())
     .then(data => {
         if (data.API_ID) {
             Swal.fire({
                 title: 'Registration Successful!',
                 html: `
                     API Key: <span id="apiKey">${data.API_KEY}</span>
                     <br>
                     <button id="copyApiKey" class="swal2-confirm swal2-styled" style="margin-top: 10px;">Copy API Key</button>
                 `,
                 icon: 'success',
                 showCancelButton: false,
                 showConfirmButton: true
             }).then(() => {
                 document.getElementById('copyApiKey').addEventListener('click', function() {
                     const apiKeyElement = document.getElementById('apiKey');
                     navigator.clipboard.writeText(apiKeyElement.textContent)
                         .then(() => {
                             Swal.fire({
                                 title: 'Copied!',
                                 text: 'API Key has been copied to clipboard.',
                                 icon: 'success'
                             });
                         })
                         .catch(err => {
                             console.error('Failed to copy API Key: ', err);
                             Swal.fire({
                                 title: 'Error',
                                 text: 'Failed to copy API Key. Please try again.',
                                 icon: 'error'
                             });
                         });
                 });
                 window.location.href = '{{ route('clientlogin') }}'; // Redirect to a specific page after registration
             });
         } else {
             Swal.fire({
                 title: 'Error',
                 text: 'Registration failed',
                 icon: 'error'
             }).then(() => {
                 window.location.reload();
             });
         }
     })
     .catch(error => {
         console.error('Error:', error);
         Swal.fire({
             title: 'Error',
             text: 'An error occurred during registration. Please try again.',
             icon: 'error'
         });
     });
 });

</script>
</body>
</html>
