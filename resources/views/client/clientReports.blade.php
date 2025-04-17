@php
    $type = $type ?? 'deposite'; // Default to 'payin' if $type is not set
@endphp

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">

@include('client/clientheader')
@include('client/clientsidebar')
<div class="main-content">
    <h2 class="page-title">Reports</h2>
    <div id="reportMessage" class="mt-4"></div>
    <form id="reportForm" action="{{ route('clientreport.generate') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-3 form-group">
                <label for="start_date">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate ?? '' }}" required>
            </div>
            <div class="col-md-3 form-group">
                <label for="end_date">End Date</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate ?? '' }}" required>
            </div>
            <div class="col-md-3 form-group">
                <label for="type">Transaction Type</label>
                <select name="type" class="form-control">
                    <option value="deposite" {{ (isset($type) && $type == 'payin') ? 'selected' : '' }}>Deposite Report</option>
                    <option value="withdraw" {{ (isset($type) && $type == 'payout') ? 'selected' : '' }}>Withdraw Report</option>
                    <!-- <option value="settlement" {{ (isset($type) && $type == 'settlement') ? 'selected' : '' }}>Commision-Settlement Report</option> -->
                </select>
            </div>
            <div class="col-md-3 form-group d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Generate Report</button>
            </div>
        </div>
    </form>

    @if(isset($results))
        <h3 class="mt-4">Report Results</h3>
        <table id="reportTable" class="table table-bordered">
            <thead>
                <tr>
                    <!-- @if($type == 'settlement')
                        <th>Sl no</th>
                        <th>Transaction Ref No</th>
                        <th>Amount (INR)</th>
                        <th>Settlement Date</th>
                    @else -->
                        <th>Sl no</th>
                        <th>Order ID</th>
                        <th>Channel</th>
                        <th>Channel ID</th>
                        <th>Status</th>
                        <th>Transaction Ref No</th>
                        <th>Amount (INR)</th>
                        <th>Created At</th>
                        <th>Transaction Type</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($results as $result)
                    <tr>
                        <!-- @if($type == 'settlement')
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $result->settlement_no }}</td>
                            <td>{{ $result->converted_amount }}</td>
                            <td>{{ $result->created_at }}</td>
                        @else -->
                        <td>{{ $loop->iteration }}</td>
                            <td>{{ $result->order_id }}</td>
                            <td>{{ $result->channel }}</td>
                            <td>{{ $result->channel_id }}</td>
                            <td>{{ $result->status }}</td>
                            <td>{{ $result->transaction_ref_no }}</td>
                            <td>{{ $result->amount_inr }}</td>
                            <td>{{ $result->created_at }}</td>
                            <td>{{ $result->transaction_type=='credited'?'DEPOSITE':'WITHDRAW' }}</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@include('client/clientfooter')

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>


<script>
$(document).ready(function() {
    var reportType = @json($type);
    
    $('#reportTable').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
        {
            extend: 'excelHtml5',
            text: 'Export Excel',
            className: 'btn btn-success',  // Apply btn-success class
            title: reportType + ' report',
            customize: function(xlsx) {
                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                var header = sheet.getElementsByTagName('row')[0];
                var cell = sheet.createElement('c');
                cell.setAttribute('t', 'inlineStr');
                var inlineStr = sheet.createElement('is');
                var text = sheet.createElement('t');
                text.textContent = reportType + ' report';
                inlineStr.appendChild(text);
                cell.appendChild(inlineStr);
                header.insertBefore(cell, header.firstChild);
            }
        },
        {
    extend: 'pdfHtml5',
    text: 'Export PDF',
    className: 'btn btn-primary',  // Apply btn-primary class
    title: reportType + ' report',
    orientation: 'landscape',  // Set orientation to landscape
    customize: function (doc) {
        doc.content[0].text = reportType + ' report';  // Set custom title
    }
},
    ]
    });
});

</script>
<style>
    .btn-success {
        background-color: green !important;
        border-color: green !important;
        color: white !important;
    }

    .btn-primary {
        background-color: blue !important;
        border-color: blue !important;
        color: white !important;
    }
</style>
