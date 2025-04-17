@include('client/clientheader')
@include('client/clientsidebar')
<style>
    /* Light mode styles */
    .support-page {
        text-align: center;
        padding: 40px;
        background-color: #f9f9f9;
        color: #333;
    }

    .support-page h2 {
        font-size: 28px;
        margin-bottom: 20px;
    }

    .support-info {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        display: inline-block;
        margin-top: 20px;
    }

    .support-info h3 {
        font-size: 24px;
        margin-bottom: 15px;
    }

    .support-info p {
        font-size: 18px;
    }

    .support-info a {
        color: #007bff;
        text-decoration: none;
    }

    .support-info a:hover {
        text-decoration: underline;
    }

    /* Dark mode styles */
    @media (prefers-color-scheme: dark) {
        .support-page {
            background-color: #FFF;
            color: #000;
        }

        .support-info {
            background-color: #FFF;
            box-shadow: 0px 2px 10px rgba(255, 255, 255, 0.1);
        }

        .support-info a {
            color: #66b2ff;
        }

        .support-info a:hover {
            text-decoration: underline;
        }
    }
</style>
<div class="main-content">
    <div class="support-page">
        <h2>Support</h2>
        <p>If you need assistance, please feel free to reach out to our support team. We are here to help you.</p>
        <div class="support-info">
            <h3>Contact Support</h3>
            <p>Email: <a href="mailto:flowtransact@gmail.com">flowtransact@gmail.com</a></p>
            <p>Our support team will respond to your query as soon as possible.</p>
        </div>
    </div>
</div>
@include('client/clientfooter')
