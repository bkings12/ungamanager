@extends('layouts.master')
@extends('layouts.app')

@section('title')
Unganisha Networks | Dashboard
@endsection

@section('content')

<div class="row">
<div class="col-md-4">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">CPU Load</h4>
        </div>
        <div class="card-body" id="cpu-load">
            <!-- Data will be inserted here by the AJAX function -->
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Free Memory</h4>
        </div>
        <div class="card-body" id="free-memory">
            <!-- Data will be inserted here by the AJAX function -->
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Free HDD Space</h4>
        </div>
        <div class="card-body" id="free-hdd-space">
            <!-- Data will be inserted here by the AJAX function -->
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">System Time</h4>
        </div>
        <div class="card-body" id="system-time">
            <!-- Data will be inserted here by the AJAX function -->
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title"> Active Users</h4>
        </div>
        <div class="card-body" id="active-users">
            <!-- Data will be inserted here by the AJAX function -->
        </div>
    </div>

</div>

<div class="col-md-12">
<button id="toggleButton">Toggle Charts</button>



    <div class="card flex justify-between">
    <div class="card-header">
        <h4 class="card-title">Traffic Monitor</h4>
    </div>
    <div class="button-dropdown">
    <select id="interface">
        <!-- Options will be populated by JavaScript -->
    </select>
</div>


    <div id="chartsContainer" style="position: relative;">

    <div id="chart2Container" style="display: none;">
    <canvas id="chart2"></canvas>
</div>
    <div class="card-body">
        <canvas id="myChart"></canvas>
    </div>
    </div>
</div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
   var myChart; // Declare the chart variable outside the function
var trafficInData = []; // Array to hold traffic in data points
var trafficOutData = []; // Array to hold traffic out data points
var labels = []; // Array to hold labels

function fetchData() {
    var selectedInterface = $('#interface').val();
    if ($('#interface option').length > 0 && !selectedInterface) {
        alert('Please select an interface.');
        return;
    }

    $.ajax({
        url: '/api/traffic-data',
        method: 'GET',
        data: {
            interface: selectedInterface // Pass the selected interface as a parameter
        },
        success: function(data) {
            console.log(data);
            var ctx = document.getElementById('myChart').getContext('2d');

            // Convert bytes per second to megabits per second
            var trafficInMbps = data.trafficIn[0] / 1000000;
            var trafficOutMbps = data.trafficOut[0] / 1000000;

            // Update the data arrays and labels
            trafficInData.push(trafficInMbps);
            trafficOutData.push(trafficOutMbps);
            labels.push(new Date().toLocaleTimeString()); // Use the current time as the label

            // If a chart already exists, destroy it
            if (myChart) {
                myChart.destroy();
            }

            // Then create a new chart
            myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Traffic In (Mbps)',
                        data: trafficInData,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        fill: false
                    }, {
            label: 'Traffic Out',
            data: trafficOutData, // Array of traffic out data points
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }]
                },
                options: {
                    responsive: true,
                    title: {
                        display: true,
                        text: 'Network Traffic'
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false,
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Time'
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Mbps'
                            }
                        }
                    }
                }
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // Handle any errors here
            console.error(textStatus, errorThrown);
        }
    });
}
$('#interface').on('change', fetchData);

// Fetch data immediately when the script runs
fetchData();

// Then fetch data every 5 seconds
setInterval(fetchData, 5000);
</script>
<script>
   function fetchResourceData() {
    $.ajax({
        url: '/api/resource', // Replace with the actual route to the resource function
        method: 'GET',
        success: function(data) {
            // The data parameter contains the data returned from the server
            // You can use this data to update your UI
            $('#cpu-load').text(data.resource['cpu-load']);
            $('#free-memory').text(Math.round(data.resource['free-memory'] / 1024 / 1024, 2) + ' MB');
            $('#free-hdd-space').text(Math.round(data.resource['free-hdd-space'] / 1024 / 1024, 2) + ' MB');
            $('#system-time').text(data.systime['time']);
            $('#active-users').text(data.active_users_count);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // Handle any errors here
            console.error(textStatus, errorThrown);
        }
    });
}

// Call the function to fetch the data immediately
fetchResourceData();

// Then call it every 5 seconds
setInterval(fetchResourceData, 5000);

$.ajax({
    url: '/api/interface-names',
    method: 'GET',
    success: function(data) {
        var select = $('#interface');
        select.empty();

        $.each(data, function(index, value) {
            select.append($('<option></option>').attr('value', value).text(value));
        });
    }
});

document.getElementById('toggleButton').addEventListener('click', function() {
    var myChartContainer = document.querySelector('.card-body');
    var chart2Container = document.getElementById('chart2Container');

    if (getComputedStyle(myChartContainer).display === 'none') {
        myChartContainer.style.display = 'block';
        chart2Container.style.display = 'none';
        // Update myChart here
        myChart.update();
    } else {
        myChartContainer.style.display = 'none';
        chart2Container.style.display = 'block';
        // Update chart2 here
        chart2.update();
    }
});
var ctx2 = document.getElementById('chart2').getContext('2d');

var chart2 = new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: labels, // Array of labels
        datasets: [{
            label: 'Traffic In',
            data: trafficInData, // Array of traffic in data points
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }, {
            label: 'Traffic Out',
            data: trafficOutData, // Array of traffic out data points
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }]
    },
    options: {
    responsive: true,
    plugins: {
        title: {
            display: true,
            text: 'Network Traffic'
        },
    },
    scales: {
        x: {
            display: true,
            title: {
                display: true,
                text: 'Time'
            }
        },
        y: {
            display: true,
            title: {
                display: true,
                text: 'Traffic (Mbps)'
            }
        }
    }

    }
});
</script>

@endsection
