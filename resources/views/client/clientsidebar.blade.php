<div class="main-wrapper">
<div class="sidebar">
    <div class="navbar-brand">
        <img src="{{ asset('uploads/Logo/logo_new.png') }}" alt="Brand Logo" id="sidebar-logo" />
    </div>
    <span class="menu-btn" onclick="toggleSidebar()">&#9776;</span> <!-- Hamburger menu icon -->

    <a href="{{route('client.dashboard')}}"><i class="fas fa-tachometer-alt"></i><span class="link-text">Dashboard</span></a>

    <div class="dropdown">
        <a href="javascript:void(0)" class="dropdown-toggle" onclick="toggleDropdown('analyticsDropdown')">
            <i class="fa-solid fa-chart-simple"></i><span class="link-text">Analytics</span>
        </a>
        <div class="dropdown-content" id="analyticsDropdown">
            <a href="{{route('client.payinAnalytics')}}"><i class="fa-solid fa-wallet"></i>Payin</a>
            <a href="{{route('client.payoutAnalytics')}}"><i class="fa-solid fa-hand-holding-dollar"></i>Payout</a>
        </div>
    </div>

    <div class="dropdown">
        <a href="javascript:void(0)" class="dropdown-toggle" onclick="toggleDropdown('financeDropdown')">
            <i class="fa-solid fa-money-bill-transfer"></i><span class="link-text text-wrap">Deposit & Withdrawal</span>
        </a>
        <div class="dropdown-content" id="financeDropdown">
            <a href="{{route('clientdeposit')}}"><i class="fa-solid fa-money-check-dollar"></i>Deposit</a>
            <a href="{{route('clientwithdraw')}}"><i class="fa-solid fa-money-bill-wheat"></i>Withdrawals</a>
        </div>
    </div>
    <div class="dropdown">
        <a href="javascript:void(0)" class="dropdown-toggle" onclick="toggleDropdown('accountDropdown')">
        <i class="fa-solid fa-briefcase"></i><span class="link-text">Accounting</span> 
        </a>
        <div class="dropdown-content" id="accountDropdown">
            <a href="{{route('clientsettlement')}}"><i class="fa-solid fa-coins"></i><span class="link-text">Settlement</span></a>
            <a href="{{route('clientwithdrawal')}}"><i class="fa-solid fa-money-bill-wheat"></i>Withdraw</a>
        </div>
    </div>
    <!-- <a href=""><i class="fa-solid fa-screwdriver-wrench"></i><span class="link-text">Others</span></a> -->
    <a href="{{route('clientreport')}}"><i class="fa-solid fa-book"></i><span class="link-text">
    Reports</span></a>
    <!-- <a href="{{route('clientsupport')}}" class="nav-link" data-title="Pay Out"><i class="fa-solid fa-chalkboard-user"></i><span class="link-text">Support</span></a> -->
    <!-- <a href="" class="nav-link" data-title="Reports"><i class="fa-solid fa-circle-info"></i><span class="link-text">To Know us</span></a> -->
    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i><span class="link-text">Logout</span>
    </a>

    <form id="logout-form" action="{{ route('clientlogout') }}" method="GET" style="display: none;">
        @csrf
    </form>
</div>
<div class="mobile-nav">
        <a href="{{route('client.analytics')}}" class="nav-link" data-title="Analytics">
        <i class="fa-solid fa-chart-simple"></i><span class="link-text">Analytics</span>
        </a>
        <a href="{{route('clientdeposit')}}" class="nav-link" data-title="Deposites">
        <i class="fa-solid fa-money-check-dollar"></i><span class="link-text">Deposites</span>
        </a>
        <a href="{{route('client.dashboard')}}" class="nav-link" data-title="Dashboard">
            <i class="fa-solid fa-gauge-high"></i><span class="link-text">Dashboard</span>
        </a>
        <a href="{{route('clientwithdraw')}}" class="nav-link" data-title="Withdraw">
        <i class="fa-solid fa-money-bill-wheat"></i><span class="link-text">Withdraw</span>
        </a>
        <a href="#" class="nav-link"   id="settlement-link" data-title="Settlements">
        <i class="fa-solid fa-coins"></i><span class="link-text">Accounting</span>
        </a>
    </div>
    <div id="toast-modal" class="toast-modal" style="display:none;">
        <button class="close-toast" onclick="closeToast()"><i class="fa-solid fa-xmark"></i></button>
    <div class="toast-content">
        <p>Select an option:</p>
        <button onclick="location.href='{{route('clientsettlement')}}'">Settlement</button>
        <button onclick="location.href='{{route('clientwithdrawal')}}'">Withdraw</button>
    </div>
</div>

    <div class="top-nav">
        <div class="top-nav-left">
            <img src="{{ asset('uploads/Logo/logo_new.png') }}" alt="Brand Logo" id="mobile-logo" />
        </div>
        <div class="text-center" style="color:#fff;">
            <h5 id="page-title"></h5>
        </div>
        <div class="dropdown">
            <button class="dropdown-toggle" style="color:#fff;">
                <i class="fa-solid fa-circle-user" style="color:#fff;"></i>
            </button>
            <div class="dropdown-menu">
                <!-- <a href="" title="Add Merchant">
                <i class="fa-solid fa-screwdriver-wrench"></i>
                   Others
                </a> -->
                <a href="{{route('clientreport')}}" title="Reports">
                    <i class="fa-solid fa-book"></i>
                    Reports
                </a>
                <!-- <a href="{{route('clientsupport')}}" title="supports">
                <i class="fa-solid fa-chalkboard-user"></i>
                    Supports
                </a> -->
                <!-- <a href="" title="Reports">
                <i class="fa-solid fa-circle-info"></i>
                   To Know Us
                </a> -->
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </div>

    </div>
</div>

<style>
  .toast-modal {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: white;
    padding: 20px;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    z-index: 9999;
    border-radius: 8px;
    text-align: center;
    width: 80%;
    max-width: 300px;
}

.toast-content {
    position: relative; /* This allows the close button to be positioned inside the modal */
}

.close-toast {
    position: absolute;
    top: 10px;
    right: 10px;
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: #000;
}

.toast-content p {
    margin-bottom: 10px;
}

.toast-content button {
    margin: 5px;
    padding: 10px 20px;
    border: none;
    background-color: #007bff;
    color: white;
    cursor: pointer;
    border-radius: 5px;
}

.toast-content button:hover {
    background-color: #0056b3;
}

</style>