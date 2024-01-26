@extends('layouts.master')
@extends('layouts.app')
@section('title' )
@endsection


@section('content')
<div class="py-12">
    <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <!-- Add Device button -->
            <a href="/admin/createdevice" class="btn btn-primary">Add Device</a>
            <!-- Loop over each device and display its details -->
            @foreach ($devices as $device)
                <div class="device-details">
                    <p>Identity: {{ $device->identity }}</p>
                    <p>IP Address: {{ $device->ip_address }}</p>
                    <p>Login: {{ $device->login }}</p>
                    <p>Password: {{ $device->password }}</p>
                    <!-- Edit and Delete buttons -->
                    <a href="/admin/devices/{{ $device->id }}/edit_device" class="btn btn-primary">Edit</a>
                    <form action="admin/devices/{{ $device->id }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
.device-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

@endsection
@section('scripts')
@endsection
