@include('client/clientheader')
@include('client/clientsidebar')
<div class="main-content">
<div class="row mb-4">
        <div class="col-lg-12 col-md-12">
            <div class="form-group">
                <label for="startDate">Start Date:</label>
                <input type="date" id="startDate" class="form-control">
            </div>
            <div class="form-group">
                <label for="endDate">End Date:</label>
                <input type="date" id="endDate" class="form-control">
            </div>
        </div>
    </div>

    <!-- Buttons for Payin and Payout -->
    <div class="row mb-4">
        <div class="col-lg-6 col-md-6">
            <button id="payinBtn" class="btn btn-primary">Payin</button>
            <button id="payoutBtn" class="btn btn-secondary">Payout</button>
        </div>
    </div>
    
    <!-- Add Charts Here -->
    <div class="row">
        <h4 class="title">PAYIN ANALYTICS</h4>
        <div class="col-lg-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Bar Chart</h5>
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Trend Line Chart</h5>
                    <canvas id="lineChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    </div>
    @include('client/clientfooter')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    var ctxBar = document.getElementById('barChart').getContext('2d');
    var barChart = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: [], // Initial empty labels
            datasets: [{
                label: 'Turnover',
                backgroundColor: 'rgba(0, 255,0, 0.8)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                data: [] // Initial empty data
            }]
        },
        options: {
            responsive: true,
            plugins: {
                zoom: {
                    zoom: {
                        wheel: {
                            enabled: true
                        },
                        pinch: {
                            enabled: true
                        },
                        drag: {
                            enabled: true
                        },
                        mode: 'xy'
                    },
                    pan: {
                        enabled: true,
                        mode: 'xy'
                    }
                }
            }
        }
    });

    var ctxLine = document.getElementById('lineChart').getContext('2d');
    var lineChart = new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: [], // Initial empty labels
            datasets: [{
                label: 'Trend',
                backgroundColor: 'rgba(255, 0, 0, 0.8)',
                borderColor: 'rgba(255, 0, 0, 1)',
                borderWidth: 1,
                data: [] // Initial empty data
            }]
        },
        options: {
            responsive: true,
            plugins: {
                zoom: {
                    zoom: {
                        wheel: {
                            enabled: true
                        },
                        pinch: {
                            enabled: true
                        },
                        drag: {
                            enabled: true
                        },
                        mode: 'xy'
                    },
                    pan: {
                        enabled: true,
                        mode: 'xy'
                    }
                }
            }
        }
    });

    // Function to update charts
    function updateCharts(data) {
        barChart.data.labels = data.labels;
        barChart.data.datasets[0].data = data.barChartData;
        lineChart.data.labels = data.labels; // Assuming trend data uses the same labels
        lineChart.data.datasets[0].data = data.barChartData; // Assuming trend data is the same as bar chart data
        barChart.update();
        lineChart.update();
    }

    // Function to fetch data based on date range and type
    function fetchData(startDate, endDate, type) {
        $.ajax({
            url: `{{ url('client/get${type}') }}`,
            method: 'GET',
            data: { startDate: startDate, endDate: endDate },
            success: function(response) {
                updateCharts(response.data);
            },
            error: function(xhr) {
                console.error(`Error fetching ${type} data`);
            }
        });
    }

    // Fetch initial data for Payin by default
    fetchData($('#startDate').val(), $('#endDate').val(), 'payin');

    // Handle Payin button click
    $('#payinBtn').click(function() {
        $('.title').text('PAYIN ANALYTICS');
        $(this).removeClass('btn-secondary').addClass('btn-primary');
        $('#payoutBtn').removeClass('btn-primary').addClass('btn-secondary');
        const startDate = $('#startDate').val();
        const endDate = $('#endDate').val();
        if (startDate && endDate) {
            fetchData(startDate, endDate, 'payin');
        } else {
            alert('Please select both start date and end date.');
        }
    });

    // Handle Payout button click
    $('#payoutBtn').click(function() {
        $('.title').text('PAYOUT ANALYTICS');
        $(this).removeClass('btn-secondary').addClass('btn-primary');
        $('#payinBtn').removeClass('btn-primary').addClass('btn-secondary');
        const startDate = $('#startDate').val();
        const endDate = $('#endDate').val();
        if (startDate && endDate) {
            fetchData(startDate, endDate, 'payout');
        } else {
            alert('Please select both start date and end date.');
        }
    });

    // Handle date range change
    $('#startDate, #endDate').change(function() {
        const startDate = $('#startDate').val();
        const endDate = $('#endDate').val();
        if ($('#payinBtn').hasClass('active')) {
            fetchData(startDate, endDate, 'payin');
        } else if ($('#payoutBtn').hasClass('active')) {
            fetchData(startDate, endDate, 'payout');
        }
    });

    // Set default active button
    $('#payinBtn').addClass('active');
});
  
</script>
