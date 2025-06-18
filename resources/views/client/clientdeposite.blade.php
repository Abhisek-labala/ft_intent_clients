@include('client/clientheader')
@include('client/clientsidebar')
<div class="main-content">
    <h2 class="page-title">Deposites</h2>
    <table id="payin-table" class="table table-striped table-bordered dt-responsive nowrap">
        <thead>
            <tr>
                <th>#</th>
                <th>ID</th>
                <th>Order ID</th>
                <th>Requested Amount</th>
                <th>Actual Amount</th>
                <th>Status</th>
                <th>Created At</th>
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

        var table = $('#payin-table').DataTable({
            ajax: {
                url: '{{ route('clientdepositedata') }}',
                data: function (d) {
                    d.start_date = $('#start-date').val();
                    d.end_date = $('#end-date').val();
                },
            },
            columns: [
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        return meta.row + 1; // Serial number based on the row index
                    },
                    title: "Sl No." // Optional: You can specify the title for the slno column
                },
                { data: 'payin_id', visible: false },
                { data: 'order_id' },
                { data: 'applied_amount' },
                { data: 'real_amount' },
                { data: 'status',
                     render: function (data, type, row) {
                        let statusClass = 'status-default';
                        if (data === 'Pending') {
                            statusClass = 'status-pending';
                        } else if (data === 'Completed') {
                            statusClass = 'status-approved';
                        } else if (data === 'Failed') {
                            statusClass = 'status-rejected';
                        }
                        return `<span class="${statusClass}">${data}</span>`;
                    }, width: '2%'
                },
                {
                    data: 'created_at',
                    render: function (data, type, row) {
                        return formatDateTime(data);
                    }
                }
            ],
            responsive: true,
            order: [[0, 'desc']],

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
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    });
</script>

<style>
    #payin-table td {
        background-color: #FFF;
        color: #000;
    }

    #payin-table th {
        background-color: #7952B3;
        color: #000;
    }
    .btn-primry
    {
         background-color: #7952B3;
        color: #fff;
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
        color: darkmagenta;
        font-weight: bold;
    }
    .status-expired {
        color: darkslateblue;
        font-weight: bold;
    }
    .status-timed_out {
        color: purple;
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
