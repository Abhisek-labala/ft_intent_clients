@include('client/clientheader')
@include('client/clientsidebar')

<div class="main-content">
    <h2 id="titlepage">Withdraw Requests</h2>
    
    <!-- Add date filters for start and end date -->
    <div class="container">
    <div class="row mb-3">
        <!-- Start Date -->
        <div class="col-12 col-sm-6 mb-3 mb-sm-0">
            <label for="start-date" class="form-label">Start Date</label>
            <input type="date" id="start-date" class="form-control" placeholder="Start Date">
        </div>

        <!-- End Date -->
        <div class="col-12 col-sm-6">
            <label for="end-date" class="form-label">End Date</label>
            <input type="date" id="end-date" class="form-control" placeholder="End Date">
        </div>
    </div>
</div>
    <div class="d-flex justify-content-end mb-3">
        <button id="create-Withdrawal-btn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createWithdrawalModal">Create Request</button>
    </div>
    <table id="Withdrawal-table" class="table table-striped">
        <thead>
            <tr>
                <th>Sl</th>
                <th>Amount</th>
                <th>Withdrawal Ref No</th>
                <th>Withdrawal Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<!-- Modal for Creating Withdrawal Request -->
<div class="modal fade" id="createWithdrawalModal" tabindex="-1" aria-labelledby="createWithdrawalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createWithdrawalModalLabel">Create Withdrawal Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                class="fa-solid fa-circle-xmark"></i></button>
            </div>
            <div class="modal-body">
                <!-- Form content for creating withdrawal request -->
                <form id="createWithdrawalForm">
                <div class="mb-3">
                        <label for="total-amount-to-settle" class="form-label">Total Available Amount</label>
                        <input type="text" class="form-control" id="total-amount-to-settle" readonly
                            placeholder="Total amount" value="0.00">
                    </div>
                    <div class="mb-3">
    <label for="withdrawal-amount" class="form-label">Amount</label>
    <input type="number" class="form-control" id="withdrawal-amount" placeholder="Enter amount to Withdraw"required>
    <!-- <div class="invalid-feedback" style="display: none;">Please enter an amount of at least 10,000.</div> -->
</div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="submitWithdrawal" class="btn btn-primary">Submit Request</button>
            </div>
        </div>
    </div>
</div>

@include('client/clientfooter')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        var table = $('#Withdrawal-table').DataTable({
            responsive: true,
            ajax: {
                url: '{{ route('withdraw.data') }}',
                data: function (d) {
                    d.start_date = $('#start-date').val();
                    d.end_date = $('#end-date').val();
                }
            },
            pageLength: 5,
            lengthMenu: [5, 10, 25],
            dom: '<"d-flex justify-content-between align-items-center"l<"d-flex"f>>rtip',
            columns: [
                {  
                    data: null,
                    name: 'serial_no',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                { data: 'withdraw_amount', name: 'withdraw_amount' },
                { data: 'withdraw_no', name: 'withdraw_no' },
                { data: 'created_at', name: 'created_at' },
                { data: 'status', name: 'status' },
            ]
        });

        $('#start-date, #end-date').change(function() {
            table.ajax.reload();
        });
        $('#create-Withdrawal-btn').click(function () {
            $.ajax({
                url: '{{ route("getTotalavailableAmount") }}',
                method: 'GET',
                success: function (response) {
                    $('#total-amount-to-settle').val(response.total_amount);
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Error fetching total amount.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            });
        });

        $('#submitWithdrawal').click(function() {
    var amount = $('#withdrawal-amount').val();
    var note = $('#withdrawal-note').val();

    $.ajax({
        url: '{{route('submitwithdraw')}}',
        method: 'POST',
        data: {
            amount: amount,
            note: note, // Include note if needed
            _token: '{{ csrf_token() }}', 
        },
        success: function(response) {
            // Handle success response
            table.ajax.reload();
            $('#createWithdrawalModal').modal('hide');

            // Show SweetAlert toast
            Swal.fire({
                icon: 'success',
                title: 'Withdrawal Successful',
                text: 'Your withdrawal has been processed.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        },
        error: function(xhr, status, error) {
            // Handle error response
            let errorMessage;
    
    // Check if the response has a JSON body
    if (xhr.responseJSON && xhr.responseJSON.message) {
        errorMessage = xhr.responseJSON.message; // Custom server error message
    } else {
        errorMessage = 'An unexpected error occurred. Please try again.'; // Fallback message
    }
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: errorMessage,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        }
    });
});

        });
</script>

<style>
    #Withdrawal-table td {
        background-color: #FFF;
        color: #000;
    }
    #Withdrawal-table th {
        background-color: #001f2e;
        color: #FFF;
    }
    #create-Withdrawal-btn {
        margin-bottom: 15px;
        border-radius: 50px;
    }
</style>
