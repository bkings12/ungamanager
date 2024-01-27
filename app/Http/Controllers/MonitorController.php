<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use RouterOS\Client;
use RouterOS\Query;
use App\Models\Device;
use App\Models\Monitor;
//use Barryvdh\DomPDF\Facade as PDF;
use PDF;


class MonitorController extends Controller
{
    public function inview()
    {
        return view('admin.monitor');
    }

    public function monitor()
    {
        // Fetch the first device from the database
        $device = Device::first();

if (!$device) {
    return response()->json(['error' => true, 'message' => 'No device found in the database.']);
}

try {
    // Initiate client with config
    $client = new Client([
        'host' => $device->ip_address,
        'user' => $device->login,
        'pass' => $device->password,
        'port' => 8728, // replace with your router's port number if it's stored in the database
    ]);

    // Build query
    $query = (new Query('/interface/print'))
        ->equal('.proplist', 'name,rx-byte,tx-byte');

    // Send query and read response
    $responses = $client->query($query)->read();

} catch (\Exception $e) {
    // Handle the exception
    return response()->json(['error' => true, 'message' => 'Device not connected ...']);
}

// Parse the responses
$interfaces = [];
foreach ($responses as $response) {
    if (in_array($response['name'], ['bridge', 'bridge-hotspot', 'ether1', 'ether2', 'ether3', 'ether4', 'ether5', 'wlan'])) {
        $interfaces[] = $response;

        // Save to database
        $monitor = new Monitor; // use your actual model name
        $monitor->name = $response['name'];
        $monitor->rx_byte = $response['rx-byte'];
        $monitor->tx_byte = $response['tx-byte'];
        $monitor->save();
    }
}
 //var_dump($interfaces);
// Pass the interfaces to the view
//return view('admin.monitor', ['interfaces' => $interfaces]);
return response()->json(['interfaces' => $interfaces]);


}

public function generateReport(Request $request) {
    // Retrieve start and end dates from the request
    $startDate = $request->startDate . ' 00:00:00';
    $endDate = $request->endDate . ' 23:59:59';
    // Validate the dates
    // ...

    // Retrieve data from the database within the date range
    $monitors = Monitor::whereBetween('created_at', [$startDate, $endDate])->get();

    // Process the data as needed
    $report = [];
    foreach ($monitors as $monitor) {
        $rx_kbps = ($monitor->rx_byte * 8) / 1_000; // convert to Kbps
        $tx_kbps = ($monitor->tx_byte * 8) / 1_000; // convert to Kbps
        $total_kbps = $rx_kbps + $tx_kbps;

        $report[] = [
            'id' => $monitor->id,
            'name' => $monitor->name,
            'rx_byte' => $monitor->rx_byte,
            'tx_byte' => $monitor->tx_byte,
            'rx_kbps' => $rx_kbps,
            'tx_kbps' => $tx_kbps,
            'total_kbps' => $total_kbps,
            'created_at' => $monitor->created_at,
            'updated_at' => $monitor->updated_at,
        ];
    }
     $pdf = PDF::loadView('admin.report', ['report' => $report])
     ->setPaper('a4', 'landscape');
    return $pdf->download('report.pdf');

    // Present the data
    //return response()->json(['report' => $report]);
    return view('admin.report', ['report' => $report]);
}
public function showGenerateReportForm() {
    return view('admin.generate');
}
}
