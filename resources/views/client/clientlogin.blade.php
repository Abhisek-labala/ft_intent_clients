<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Client Login</title>
  <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Laravel CSRF Token -->
  <style>
    * { box-sizing: border-box; }
    body {
      min-height: 100vh;
      margin: 0;
      font-family: 'Raleway', sans-serif;
    }

    .container {
      position: absolute;
      width: 100%;
      height: 100%;
      overflow: hidden;
    }

    .top::before,
    .top::after,
    .bottom::before,
    .bottom::after {
      content: '';
      display: block;
      position: absolute;
      width: 200vmax;
      height: 200vmax;
      top: 50%;
      left: 50%;
      margin-top: -100vmax;
      transform-origin: 0 50%;
      transition: all 0.5s cubic-bezier(0.445, 0.05, 0, 1);
      z-index: 10;
      opacity: 0.65;
      transition-delay: 0.2s;
    }

    .top::before { transform: rotate(45deg); background: #e46569; }
    .top::after { transform: rotate(135deg); background: #ecaf81; }
    .bottom::before { transform: rotate(-45deg); background: #60b8d4; }
    .bottom::after { transform: rotate(-135deg); background: #7952B3; }

    .center {
      position: absolute;
      width: 400px;
      height: 400px;
      top: 50%;
      left: 50%;
      margin-left: -200px;
      margin-top: -200px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 30px;
      opacity: 0;
      transition: all 0.5s cubic-bezier(0.445, 0.05, 0, 1);
      transition-delay: 0s;
      color: #333;
      background: rgba(255,255,255,0.85);
      border-radius: 8px;
    }

    .center input {
      width: 100%;
      padding: 15px;
      margin: 5px;
      border-radius: 4px;
      border: 1px solid #ccc;
      font-family: inherit;
    }

    .center button {
      padding: 12px 20px;
      width: 100%;
      margin-top: 10px;
      font-weight: bold;
      background-color: #7952B3;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .center button:hover {
      background-color: #2c3799;
    }

    .container:hover .top::before,
    .container:hover .top::after,
    .container:active .top::before,
    .container:active .top::after,
    .container:hover .bottom::before,
    .container:hover .bottom::after,
    .container:active .bottom::before,
    .container:active .bottom::after {
      margin-left: 200px;
      transform-origin: -200px 50%;
      transition-delay: 0s;
    }

    .container:hover .center,
    .container:active .center {
      opacity: 1;
      transition-delay: 0.2s;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="top"></div>
    <div class="bottom"></div>
    <div class="center">
      <h2>Please Sign In</h2>
      <form id="loginForm" method="POST" action="{{ route('clientlogin') }}">
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit">Login</button>
      </form>
    </div>
  </div>

  <script>
    document.getElementById('loginForm').addEventListener('submit', function (e) {
      e.preventDefault();

      const form = this;
      const formData = new FormData(form);
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      fetch(form.action, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.redirect) {
          Swal.fire({
            toast: true,
            icon: 'success',
            title: data.message || 'Login successful',
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
          }).then(() => {
            window.location.href = data.redirect;
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Login Failed',
            text: data.message || 'Invalid email or password'
          });
        }
      })
      .catch(err => {
        console.error(err);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Something went wrong. Please try again.'
        });
      });
    });
  </script>
</body>
</html>
