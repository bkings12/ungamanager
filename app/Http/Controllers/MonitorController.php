<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use RouterOS\Client;
use RouterOS\Query;
use App\Models\Device;

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
    }
}
 //var_dump($interfaces);
// Pass the interfaces to the view
//return view('admin.monitor', ['interfaces' => $interfaces]);
return response()->json(['interfaces' => $interfaces]);
    }
}
