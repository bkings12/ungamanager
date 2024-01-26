@extends('layouts.master')
@extends('layouts.app')
@section('title' )
@endsection

@section('content')
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
setInterval(fetchInterfaceData, 5000);
</script>
@endsection
