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
            --background: #192A51;
            --color: #FFFFFF;
            --primary-color: #967AA1;
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

        .login-container form input {
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

        .login-container form input:focus {
            box-shadow: 0 0 16px 1px rgba(0, 0, 0, 0.2);
        }

        .login-container form button {
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

        .login-container form button:hover {
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

        .illustration {
            position: absolute;
            top: -14%;
            right: -2px;
            width: 90%;
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

        @keyframes wobble {
            0% { transform: scale(1.025); }
            25% { transform: scale(1); }
            75% { transform: scale(1.025); }
            100% { transform: scale(1); }
        }

        #clientlogo {
            height: 150px;
            width: 150px;
            margin-left: auto;
            margin-right: auto;
            display: block;
        }
    </style>
</head>
<body>
<section class="container">
    <div class="login-container">
        <img src="{{asset('uploads/Logo/logo_new.png')}}" alt="logo" id="clientlogo">
        <div class="form-container">
            <h1 class="opacity">LOGIN</h1>
            <form id="clientLogin" enctype="multipart/form-data" action="{{ route('clientlogin') }}" method="POST">
                @csrf
                <input type="text" name="email" id="email" placeholder="Email Id" required />
                <input type="password" name="password" id="password" placeholder="PASSWORD" required />
                <button class="opacity" type="submit">SUBMIT</button>
            </form>
        </div>
        <div class="circle circle-two"></div>
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

    document.getElementById('clientLogin').addEventListener('submit', function (event) {
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
            if (data.message) {
                if (data.redirect) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Login successful!',
                        showConfirmButton: false,
                        timer: 3000
                    }).then(() => {
                        window.location.href = data.redirect;
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message,
                        icon: 'error'
                    }).then(()=>{
                        window.location.reload();
                    });
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'An error occurred during login. Please try again.',
                icon: 'error'
            });
        });
    });
</script>
</body>
</html>
