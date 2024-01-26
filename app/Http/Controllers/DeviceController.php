<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use RouterOS\Client;
use RouterOS\Config;

class DeviceController extends Controller
{



    // ...

    public function store(Request $request)
{
    $data = $request->all();

    $validator = Validator::make($data, [
        'ip_address' => 'required',
        'login' => 'required',
        'password' => 'required',
    ]);

    if($validator->fails()) return response()->json(['error' => true, 'message' => $validator->errors()], 400);

    try {
        $client = new Client([
            'host' => $data['ip_address'],
            'user' => $data['login'],
            'pass' => $data['password'],
        ]);

        $identityQuery = (new Query('/system/identity/print'));
        $identity = $client->query($identityQuery)->read()[0]['name'];

        $store_device_data = [
            'identity' => $identity,
            'ip_address' => $data['ip_address'],
            'login' => $data['login'],
            'password' => $data['password'],
            'connect' => true
        ];

        $store_device = new Device;
        $store_device->identity = $store_device_data['identity'];
        $store_device->ip_address = $store_device_data['ip_address'];
        $store_device->login = $store_device_data['login'];
        $store_device->password = $store_device_data['password'];
        $store_device->connect = $store_device_data['connect'];
        $store_device->save();

        return redirect()->route('devices.index');

    } catch (\Exception $e) {
        return response()->json(['error' => true, 'message' => 'Device not connected ...'], 500);
    }
}

public function createdevice()
{
    return view('admin.createdevice');
}

public function edit($id)
{
    $device = Device::find($id);
    return view('admin.editdevice', ['device' => $device]);
}

public function update(Request $request, $id)
{
    $device = Device::find($id);
    $device->ip_address = $request->ip_address;
    $device->login = $request->login;
    $device->password = $request->password;
    $device->save();
    return redirect('/devices')->with('status', 'Device data has been updated');
}

public function destroy($id)
{
    $device = Device::find($id);
    $device->delete();
    return redirect('/devices')->with('status', 'Device data has been deleted');
}

public function index()
{
    $devices = Device::all();
    $device = Device::first();
    return view('admin.devices', ['devices' => $devices]);
}

}

