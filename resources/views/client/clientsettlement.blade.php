@include('client/clientheader')
@include('client/clientsidebar')

<div class="main-content">
    <h2 id="titlepage">Settlement Requests</h2>
    <div class="d-flex justify-content-end mb-3">
        <button id="create-settlement-btn" class="btn btn-primay" data-bs-toggle="modal"
            data-bs-target="#createSettlementModal">Create Request</button>
    </div>
    <table id="settlement-table" class="table table-striped">
        <thead>
            <tr>
                <th>Sl</th>
                <th>Raised Amount</th>
                <th>Transaction Fees</th>
                <th>Final Amount</th>
                <th>Settlement Number</th>
                <th>Settlement Date</th>
                <th>Settlement Account</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<!-- Modal for Creating Settlement Request -->
<div class="modal fade" id="createSettlementModal" tabindex="-1" aria-labelledby="createSettlementModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#7952B3">
                <h5 class="modal-title" style="color:#FFF" id="createSettlementModalLabel">Create Settlement Request
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                        class="fa-solid fa-circle-xmark"></i></button>
            </div>
            <div class="modal-body">
                <!-- Form content for creating settlement request -->
                <form id="createSettlementForm" novalidate>
                    <div class="mb-3">
                        <label for="total-amount-to-settle" class="form-label">Total Available Amount</label>
                        <input type="text" class="form-control" id="total-amount-to-settle" readonly
                            placeholder="Total amount" value="0.00">
                    </div>
                    <div class="mb-3">
                        <label for="settlement-amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="settlement-amount" required
                            placeholder="Enter amount to settle..">
                        <div class="invalid-feedback">
                            Please enter a valid amount that is less than or equal to the available amount.
                        </div>
                    </div>

                    <h5>Payment Details</h5>
                    <div class="mb-3">
                        <label for="payment-method" class="form-label">Payment Method</label>
                        <select class="form-control" id="payment-method" required>
                            <option value="">Choose...</option>
                            <option value="bank">Bank Account</option>
                            <option value="crypto">Crypto Address</option>
                        </select>
                        <div class="invalid-feedback">
                            Please select a payment method.
                        </div>
                    </div>

                    <!-- Dynamic content based on payment method -->
                    <div id="payment-details-container">
                        <!-- Bank Account details -->
                        <div id="bank-details" class="payment-details mb-3" style="display: none;">
                            <label for="bank-name" class="form-label">Bank Name</label>
                            <input type="text" class="form-control" id="bank-name" placeholder="Enter bank name"
                                required>

                            <label for="bank-account" class="form-label">Bank Account Number</label>
                            <input type="text" class="form-control" id="bank-account"
                                placeholder="Enter bank account number" required>

                            <label for="accountholder" class="form-label mt-2">Account Holder Name</label>
                            <input type="text" class="form-control" id="accountholder"
                                placeholder="Enter account holder name" required>

                            <label for="bank-ifsc" class="form-label mt-2">IFSC Code</label>
                            <input type="text" class="form-control" id="bank-ifsc" placeholder="Enter IFSC code"
                                required>
                        </div>

                        <!-- Crypto Address details -->
                        <div id="crypto-details" class="payment-details mb-3" style="display: none;">
                            <label for="crypto-accountname" class="form-label">Account Holder Name</label>
                            <input type="text" class="form-control" id="crypto-accountname"
                                placeholder="Enter crypto address" required>

                            <label for="crypto-address" class="form-label">Crypto Address</label>
                            <input type="text" class="form-control" id="crypto-address"
                                placeholder="Enter crypto address" required>

                            <label for="crypto-type" class="form-label mt-2">Crypto Type</label>
                            <input type="text" class="form-control" id="crypto-type"
                                placeholder="Enter type (e.g., Bitcoin, Ethereum)" required>
                        </div>
                    </div>

                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="button" id="submitSettlement" class="btn btn-primay">Submit Request</button>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Include SweetAlert2 -->

<script>
    $(document).ready(function () {
        $('#payment-method').change(function () {
            var selectedMethod = $(this).val();

            // Hide all payment detail sections initially
            $('.payment-details').hide();

            // Show the relevant section based on the selected method
            if (selectedMethod === 'bank') {
                $('#bank-details').show();
            } else if (selectedMethod === 'crypto') {
                $('#crypto-details').show();
            }
        });

        var table = $('#settlement-table').DataTable({
            responsive: true,
            ajax: {
                url: '{{ route('settlements.data') }}',
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
                { data: 'converted_amount', name: 'converted_amount' },
                { data: 'settlement_transaction_fees', name: 'settlement_transaction_fees' },
                { data: 'final_settled_amount', name: 'final_settled_amount' },
                { data: 'settlement_no', name: 'settlement_no' },
                { data: 'created_at', name: 'created_at' },
                {
            data: 'payment_details',
            name: 'payment_details',
            render: function (data, type, row) {
                if (type === 'display' && data) {
                    // Decode HTML entities
                    var decodedData = $('<div/>').html(data).text();
                    // Parse the JSON string
                    try {
                        var paymentInfo = JSON.parse(decodedData);

                        // Construct formatted HTML
                        var formattedHtml = '';
                        if (paymentInfo.bank_name) formattedHtml += `<br><strong>Bank Name:</strong> ${paymentInfo.bank_name}<br>`;
                        if (paymentInfo.bank_account) formattedHtml += `<strong>Bank Account:</strong> ${paymentInfo.bank_account}<br>`;
                        if (paymentInfo.account_holder) formattedHtml += `<strong>Account Holder:</strong> ${paymentInfo.account_holder}<br>`;
                        if (paymentInfo.ifsc_code) formattedHtml += `<strong>IFSC Code:</strong> ${paymentInfo.ifsc_code}<br>`;
                        if (paymentInfo.crypto_account_name) formattedHtml += `<br><strong>Crypto Account Name:</strong> ${paymentInfo.crypto_account_name}<br>`;
                        if (paymentInfo.crypto_address) formattedHtml += `<strong>Crypto Address:</strong> ${paymentInfo.crypto_address}<br>`;
                        if (paymentInfo.crypto_type) formattedHtml += `<strong>Crypto Type:</strong> ${paymentInfo.crypto_type}<br>`;

                        return formattedHtml || 'No details available'; // Show message if no details
                    } catch (e) {
                        return 'Invalid data'; // Handle any JSON parsing errors
                    }
                }
                return 'No details available'; // If data is empty or null
            }
        },

                { data: 'status', name: 'status' }
            ]
        });

        $('#start-date, #end-date').change(function () {
            table.ajax.reload();
        });

        $('#create-settlement-btn').click(function () {
            $.ajax({
                url: '{{ route("getTotalAmount") }}',
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

        $('#submitSettlement').click(function () {
            // Collect form data
            var amount = parseFloat($('#settlement-amount').val());
            var totalAmount = parseFloat($('#total-amount-to-settle').val());
            var paymentMethod = $('#payment-method').val();
            var paymentDetails = {};

            // Validate amount
            if (isNaN(amount) || amount <= 0 || amount > totalAmount) {
                $('#settlement-amount').addClass('is-invalid');
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Amount',
                    text: 'Please enter a valid amount that is less than or equal to the available amount.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                return;
            } else {
                $('#settlement-amount').removeClass('is-invalid').addClass('is-valid');
            }

            // Validate payment method and details
            if (paymentMethod === 'bank') {
                paymentDetails = {
                    bank_name: $('#bank-name').val(),
                    bank_account: $('#bank-account').val(),
                    account_holder: $('#accountholder').val(),
                    ifsc_code: $('#bank-ifsc').val()
                };

                let valid = true;
                // Check if any field is empty
                $.each(paymentDetails, function (key, value) {
                    if (!value) {
                        $(`#${key}`).addClass('is-invalid');
                        valid = false;
                    } else {
                        $(`#${key}`).removeClass('is-invalid').addClass('is-valid');
                    }
                });

                if (!valid) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Incomplete Details',
                        text: 'Please fill in all bank details.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    return;
                }
            } else if (paymentMethod === 'crypto') {
                paymentDetails = {
                    crypto_account_name: $('#crypto-accountname').val(),
                    crypto_address: $('#crypto-address').val(),
                    crypto_type: $('#crypto-type').val()
                };

                let valid = true;
                // Check if any field is empty
                $.each(paymentDetails, function (key, value) {
                    if (!value) {
                        $(`#${key}`).addClass('is-invalid');
                        valid = false;
                    } else {
                        $(`#${key}`).removeClass('is-invalid').addClass('is-valid');
                    }
                });

                if (!valid) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Incomplete Details',
                        text: 'Please fill in all crypto details.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    return;
                }
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Payment Method Required',
                    text: 'Please select a payment method.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                return;
            }

            // AJAX request
            $.ajax({
                url: '{{ route("submitSettlement") }}', // Replace with the correct route
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // Add CSRF token for security
                    amount: amount,
                    payment_method: paymentMethod,
                    payment_details: paymentDetails
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Settlement request submitted successfully.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    table.ajax.reload();
                    $('#createSettlementForm')[0].reset();
                    $('#createSettlementModal').modal('hide');
                },
                error: function (xhr, status, error) {
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
                        timer: 3000
                    });
                }
            });
        });
    });
</script>

<style>
    #settlement-table td {
        background-color: #FFF;
        color: #000;
    }
    #settlement-table th {
        background-color: #7952B3;
        color: #000;
    }
    #create-settlement-btn {
        margin-bottom: 15px;
        border-radius: 50px;
     background-color: #7952B3;
        color:#FFF;
    }
    .btn-primay
    {
        background-color: #7952B3;
        color:#FFF;
    }
</style>
