@include('client/clientheader')
@include('client/clientsidebar')

<style>


    .card {
        border-radius: 10px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .card-body {
        padding: 20px;
        text-align: center;
    }

    .card-title {
        font-size: 1.25rem;
        margin-bottom: 10px;
        color: #fff;
    }

    .card-text {
        font-size: 1rem;
        color: #fff;
    }

    .card-icon {
        font-size: 2rem;
        margin-bottom: 15px;
        color: #fff;
    }

    .card-primary {
        background-color:rgb(26, 104, 187);
    }

    .card-success {
        background-color:rgb(6, 212, 54);
    }

    .card-danger {
        background-color:rgb(116, 55, 167);
    }

    .card-warning {
        background-color: #ffc107;
    }

    .card-info {
        background-color: #17a2b8;
    }
    .card-dark {
        background-color: #001f2e;
    }
    .card-dark2 {
        background-color:rgb(235, 137, 8);
    }

    h1 {
        margin-top: 10px;
        margin-bottom: 20px;
        font-size: 2rem;
    }
</style>
<div class="main-content">
    <h1>Deposit Details</h1>
    <div class="row">
        <!-- Card for Overall Payment -->
        <div class="col-lg-4 col-md-6">
            <div class="card card-primary">
                <div class="card-body">
                    <i class="fas fa-exchange-alt card-icon"></i>
                    <h5 class="card-title">Overall Payment</h5>
                    <p class="card-text">Total: {{ $overaldeposit ??0.00 }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card card-success">
                <div class="card-body">
                    <i class="fas fa-exchange-alt card-icon"></i>
                    <h5 class="card-title">Success Deposit</h5>
                    <p class="card-text">Total: {{ $resolvedpayment ??0.00 }}</p>
                </div>
            </div>
        </div>
        <!-- Card for Transaction Fees -->
        <div class="col-lg-4 col-md-6">
            <div class="card card-dark">
                <div class="card-body">
                    <i class="fas fa-percent card-icon"></i>
                    <h5 class="card-title">Comission On Deposit ({{$deposite_percentage}}%)</h5>
                    <p class="card-text">Total: {{$deposite_percentage_amount??'0.00'}}</p>
                </div>
            </div>
        </div>
        </div>
    <h1>Withdraw Details</h1>
    <div class="row">
        <!-- Card for Overall Payment -->
        <div class="col-lg-4 col-md-6">
            <div class="card card-info">
                <div class="card-body">
                    <i class="fas fa-exchange-alt card-icon"></i>
                    <h5 class="card-title">Withdrawal Load</h5>
                    <p class="card-text">Total: {{ $withdraw ??'0.00' }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card card-dark">
                <div class="card-body">
                <i class="fas fa-percent card-icon"></i>
                    <h5 class="card-title">Comission({{$withdraw_percentage}}%)</h5>
                    <p class="card-text">Total: {{$withdrawpercamount ?? '0.00'}} </p>
                </div>
            </div>
        </div>

    </div>
</div>

@include('client/clientfooter')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
