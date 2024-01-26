<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use RouterOS\Client;
use RouterOS\Query;

class DashboardController extends Controller
{
    public function indexdash()
    {
        return view('admin.dashboard');
    }

    public function resource()
{
    $device = Device::first();

    if (!$device) {
        return response()->json(['error' => true, 'message' => 'No device found in the database.']);
    }

    try {
        $client = new Client([
            'host' => $device->ip_address,
            'user' => $device->login,
            'pass' => $device->password,
            'port' => 8728, // replace with your router's port number if it's stored in the database
        ]);

        // Rest of your code...

    } catch (\Exception $e) {
        // Handle the exception
        return response()->json(['error' => true, 'message' => 'Device not connected ...']);
    }


        $resourceQuery = (new Query('/system/resource/print'));
        $resource = $client->query($resourceQuery)->read()[0];

        $systimeQuery = (new Query('/system/clock/print'));
        $systime = $client->query($systimeQuery)->read()[0];

        $activeUsersQuery = (new Query('/ip/hotspot/active/print'));
        $active_users = $client->query($activeUsersQuery)->read();
        $active_users_count = count($active_users);

        return response()->json(['resource' => $resource, 'systime' => $systime, 'active_users_count' => $active_users_count]);
    }

    public function getTrafficData()
    {
        $device = Device::first();

        if (!$device) {
            return response()->json(['error' => true, 'message' => 'No device found in the database.']);
        }

        try {
            $client = new Client([
                'host' => $device->ip_address,
                'user' => $device->login,
                'pass' => $device->password,
                'port' => 8728, // replace with your router's port number if it's stored in the database
            ]);

            // Rest of your code...

        } catch (\Exception $e) {
            // Handle the exception
            return response()->json(['error' => true, 'message' => 'Device not connected ...']);
        }
        $query = (new Query('/interface/monitor-traffic'))
            ->equal('interface', 'ether1')
            ->equal('once', true);

        $interfaceTraffic = $client->query($query)->read()[0];
        $trafficIn = $interfaceTraffic['rx-bits-per-second'];
        $trafficOut = $interfaceTraffic['tx-bits-per-second'];
        $data = [
            'labels' => ['Traffic In', 'Traffic Out'],
            'trafficIn' => [$trafficIn],
            'trafficOut' => [$trafficOut],
        ];

        return response()->json($data);
    }
}
