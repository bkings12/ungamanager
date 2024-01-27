@extends('layouts.master')
@extends('layouts.app')
@section('title' )
@endsection

@section('content')
<select id="intervalSelect">
    <option value="5">5 seconds</option>
    <option value="60">1 minute</option>
    <option value="300">5 minutes</option>
    <option value="600">10 minutes</option>
    <option value="900">15 minutes</option>
</select>
<div id="interface-table">
    <!-- The table will be inserted here by JavaScript -->
</div>
@endsection

@section('scripts')
<script>

let prevData = null;

function formatBytesToKbps(bytesPerSecond) {
    let kbps = bytesPerSecond * 8 / 1024;
    if (kbps < 1024) {
        return kbps.toFixed(2) + ' Kbps';
    } else {
        return (kbps / 1024).toFixed(2) + ' Mbps';
    }
}

function fetchInterfaceData() {
    $.ajax({
        url: '/api/monitor',
        method: 'GET',
        success: function(data) {
    let table = '<table class="table"><thead><tr><th scope="col">Interface Name</th><th scope="col">Received Bytes</th><th scope="col">Transmitted Bytes</th></tr></thead><tbody>';
    for (let i = 0; i < data.interfaces.length; i++) {
        let iface = data.interfaces[i];
        let rxBytes = iface['rx-byte'];
        let txBytes = iface['tx-byte'];
        if (prevData) {
            let prevRxBytes = prevData.interfaces[i]['rx-byte'];
            let prevTxBytes = prevData.interfaces[i]['tx-byte'];
            rxBytes = (rxBytes - prevRxBytes) / 5; // bytes per second
            txBytes = (txBytes - prevTxBytes) / 5; // bytes per second
        }
        let rxSpeed = formatBytesToKbps(rxBytes);
        let txSpeed = formatBytesToKbps(txBytes);
        table += '<tr><td>' + iface.name + '</td><td style="color:' + getColor(rxSpeed) + ';">' + rxSpeed + '</td><td style="color:' + getColor(txSpeed) + ';">' + txSpeed + '</td></tr>';
    }
    table += '</tbody></table>';
    document.getElementById('interface-table').innerHTML = table;
    prevData = data; // store the current data for the next iteration
},
        error: function(jqXHR, textStatus, errorThrown) {
            console.error(textStatus, errorThrown);
        }
    });
}

function getColor(speed) {
    let value = parseFloat(speed);
    let unit = speed.match(/\D+$/)[0];
    if (unit === ' Kbps') {
        if (value < 700) {
            return 'green';
        } else if (value < 1024) {
            return 'orange';
        } else {
            return 'red';
        }
    } else if (unit === ' Mbps') {
        if (value < 1.5) {
            return 'yellow';
        } else {
            return 'red';
        }
    }
}

fetchInterfaceData();
var intervalSelect = document.getElementById('intervalSelect');

// Load the selected interval from localStorage
var savedInterval = localStorage.getItem('selectedInterval');
if (savedInterval) {
    intervalSelect.value = savedInterval;
}

intervalSelect.addEventListener('change', function() {
    // Clear the existing interval
    if (window.fetchInterfaceDataInterval) {
        clearInterval(window.fetchInterfaceDataInterval);
    }

    // Set a new interval with the selected value
    var seconds = parseInt(this.value);
    window.fetchInterfaceDataInterval = setInterval(fetchInterfaceData, seconds * 1000);

    // Save the selected interval to localStorage
    localStorage.setItem('selectedInterval', this.value);
});

// Trigger the change event to set the initial interval
intervalSelect.dispatchEvent(new Event('change'));
</script>
@endsection
