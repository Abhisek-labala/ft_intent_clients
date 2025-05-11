@include('client/clientheader')
@include('client/clientsidebar')
<div class="main-content">
    <h2 class="page-title">Withdrawals</h2>
    <div class="row mb-3 statusFilters">
        <div class="col-md-3">
            <label>Filter Status</label>
            <select id="statusFilter" class="form-select">
            <option value="">All</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="expired">Expired</option>
                <option value="timed_out">Timed Out</option>
                <option value="rejected">Rejected</option>
                <option value="failed">Failed</option>
                <option value="wrong-raise">Wrong Raise</option>
            </select>
        </div>
    </div>
    <!-- <button type="button" class="btn btn-success addpayout-btn" data-bs-toggle="modal" data-bs-target="#addTransactionModal">Add Transaction</button> -->
    <table id="payout-table" class="table table-striped table-bordered dt-responsive nowrap">
        <thead>
            <tr>
                <th>#</th>
                <th>ID</th>
                <th>Order ID</th>
               <th>Requested Amount</th>
               <th>Actual Amount</th>
                <th>Username</th>
                <th>Account No</th>
                <th>Bank Name</th>
                <th>Bank code</th>
                <th>IFSC code</th>
                <th>Status</th>
                <th>created_at</th>
            </tr>
        </thead>
    </table>
</div>


@include('client/clientfooter')

<!-- Add the necessary scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        $('#statusFilter').on('change', function () {
            var selectedStatus = $(this).val();  // Get selected status
            if (selectedStatus) {
                table.column(8).search(selectedStatus).draw();  // Assuming the status column is the 6th column (index 5)
            } else {
                table.column(8).search('').draw();  // Clear search if no status is selected
            }
        });

        function formatDateTime(dateTime) {
            const date = new Date(dateTime);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const seconds = String(date.getSeconds()).padStart(2, '0');
            return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
        }
        var table = $('#payout-table').DataTable({
            ajax: {
                url: '{{ route('clientwithdrawdata') }}',
            },
            columns: [
                {
                    data: 'id',
                    render: function (data, type, row, meta) {
                        return meta.row + 1; // Serial number based on the row index
                    },
                    title: "Sl No." // Optional: You can specify the title for the slno column
                },
                { data: 'id', visible: false },
                { data: 'our_client_order_id' },
                { data: 'remittance_amount' },
                { data: 'apply_amount' },
                { data: 'apply_user_name' },
                { data: 'apply_account' },
                { data: 'apply_bank_name' },
                { data: 'apply_bank_code' },
                { data: 'apply_ifsc' },
                {
                    data: 'status_level',
                },
                {
                    data: 'created_at',
                    render: function (data, type, row) {
                        return formatDateTime(data);
                    }
                },
            ],
            responsive: true,
            order: [[0, 'desc']],
            dom: 'Bfrtip',
            pageLength: 10,
            scrollX:true,
              scrollCollapse: true, // Prevent excessive scrolling
                fixedColumns: true,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    className: 'btn-success'
                },
                {
                    extend: 'print',
                    text: 'Print',
                    className: 'btn-primary'
                }
            ],
        });

        function showToast(type, title, text) {
            Swal.fire({
                icon: type,
                title: title,
                text: text,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }
    });

</script>
<style>
    #payout-table td {
        background-color: #FFF;
        color: #000;
    }

    #payout-table th {
        background-color: #7952B3;
        color: #000;
    }

    .status-approved {
        color: green;
        font-weight: bold;
    }

    .status-rejected {
        color: red;
        font-weight: bold;
    }

    .status-pending {
        color: orange;
        font-weight: bold;
    }

    .status-make_up {
        color: darkslateblue;
        font-weight: bold;
    }

    .status-voided {
        color: purple;
        font-weight: bold;
    }

    .status-default {
        color: gray;
        font-weight: bold;
    }
</style>
