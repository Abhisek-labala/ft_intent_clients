<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">

@include('client/clientheader')
@include('client/clientsidebar')

<div class="main-content">
    <h2>Reports</h2>
    <div id="reportMessage" class="mt-4"></div>

    <form id="reportForm" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-3 form-group">
                <label for="start_date">Start Date</label>
                <input type="date" name="start_date" class="form-control" required>
            </div>
            <div class="col-md-3 form-group">
                <label for="end_date">End Date</label>
                <input type="date" name="end_date" class="form-control" required>
            </div>
            <div class="col-md-3 form-group">
                <label for="type">Transaction Type</label>
                <select name="type" class="form-control" required>
                    <option value="payin">Payin Report</option>
                    <option value="payout">Payout Report</option>
                </select>
            </div>
            <div class="col-md-3 form-group d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Generate Report</button>
            </div>
        </div>
    </form>

    <h3 class="mt-4">Report Results</h3>
    <table id="reportTable" class="table table-bordered">
        <thead>
            <tr id="reportHeaders">
                <!-- Headers will be inserted dynamically here -->
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

@include('client/clientfooter')

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script>
    $(document).ready(function () {
        // Dynamically define the headers and columns for payin and payout reports
        const columns = {
            payin: [
                { title: 'S.NO', data: 'id' },
                { title: 'Order ID', data: 'order_id' },
                { title: 'Transaction ID', data: 'our_client_order_id' },
                { title: 'Status', data: 'status_label' },
                { title: 'Requested Amount', data: 'apply_amount' },
                { title: 'Final Amount', data: 'realamount' },
                { title: 'Created At', data: 'created_at' }
            ],
            payout: [
                { title: 'S.NO', data: 'id' },
                { title: 'Order ID', data: 'order_id' },
                { title: 'Transaction ID', data: 'our_client_order_id' },
                { title: 'Status', data: 'status_level' },
                { title: 'Requested Amount', data: 'remittance_amount' },
                { title: 'Final Amount', data: 'apply_amount' },
                { title: 'A/c Holder Name', data: 'apply_user_name' },
                { title: 'Account No.', data: 'apply_account' },
                { title: 'Bank Name', data: 'apply_bank_name' },
                { title: 'IFSC', data: 'apply_ifsc' },
                { title: 'Created At', data: 'created_at' }
            ]
        };

        // Variable to store the DataTable instance
        let reportTable = null;

        // On form submission, generate the report
        $('#reportForm').on('submit', function (e) {
            e.preventDefault();
            let formData = $(this).serialize();
            let reportType = $('select[name="type"]').val();

            // Clear previous table if it exists
            if ($.fn.DataTable.isDataTable('#reportTable')) {
                $('#reportTable').DataTable().destroy();
                $('#reportTable').empty();
            }

            // Recreate the table structure
            $('#reportTable').html(`
                <thead>
                    <tr id="reportHeaders"></tr>
                </thead>
                <tbody></tbody>
            `);

            // Set the correct headers based on the report type
            $('#reportHeaders').empty();
            columns[reportType].forEach(function(col) {
                $('#reportHeaders').append('<th>' + col.title + '</th>');
            });

            // Initialize DataTable with the appropriate columns
            reportTable = $('#reportTable').DataTable({
                responsive: true,
                autoWidth: false,
                 scrollX: true,  // Enable horizontal scrolling
                scrollCollapse: true, // Prevent excessive scrolling
                fixedColumns: true,
                destroy: true, // Ensure previous instance is destroyed
                processing: true,
                language: { emptyTable: "No transactions available" },
                columns: columns[reportType], // Set the columns dynamically
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Export Excel',
                        className: 'btn btn-success',
                        title: function () {
                            return $('select[name="type"]').val() + ' Report';
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'Export PDF',
                        className: 'btn btn-primary',
                        title: function () {
                            return $('select[name="type"]').val() + ' Report';
                        },
                        orientation: 'landscape',
                        pageSize: 'A4',
                        customize: function (doc) {
                            doc.defaultStyle.fontSize = 6;
                            doc.styles.tableHeader.fontSize = 8;
                            doc.styles.title.fontSize = 10;
                            doc.content[1].margin = [10, 10, 10, 10];
                            doc.content[1].layout = {
                                hLineWidth: () => 0.5,
                                vLineWidth: () => 0.5,
                                hLineColor: () => '#ccc',
                                vLineColor: () => '#ccc',
                                paddingLeft: () => 2,
                                paddingRight: () => 2,
                                paddingTop: () => 2,
                                paddingBottom: () => 2
                            };
                        }
                    }
                ]
            });

            Swal.fire({
                title: 'Generating Report...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '/clientreport/generate',
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                success: function (response) {
                    Swal.close();

                    if (response.success && response.results.length > 0) {
                        // Clear any existing data
                        reportTable.clear();

                        // Add new data
                        response.results.forEach((item) => {
                            reportTable.row.add(item).draw(false);
                        });

                        // Redraw the table to reflect changes
                        reportTable.draw();
                    } else {
                        reportTable.clear().draw();
                        Swal.fire({
                            icon: 'info',
                            title: 'No Results Found',
                            text: response.message || 'No data found for the selected criteria.',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                },
                error: function (xhr) {
                    Swal.close();
                    $('#reportMessage').html('<div class="alert alert-danger">Error generating report. Please try again.</div>');
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>

<style>
    .btn-success {
        background-color: green !important;
        border-color: green !important;
        color: #000 !important;
    }

    .btn-primary {
        background-color: #7952B3 !important;
        border-color: #7952B3 !important;
        color: #000 !important;
        font-weight: 500;
    }
    #reportHeaders{
         background-color: #7952B3;
        color: #000;
    }
</style>
