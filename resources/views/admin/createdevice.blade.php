@extends('layouts.master')
@extends('layouts.app')



@section('content')
<div class="py-12">
    <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
        <form action="{{ route('devices.store') }}" method="post">
                @csrf
                <div class="mb-4">
                    <label for="ip_address">{{ __('IP Address') }}</label>
                    <input id="ip_address" class="block mt-1 w-full" type="text" name="ip_address" required autofocus />
                </div>
                <div class="mb-4">
                    <label for="login">{{ __('Login') }}</label>
                    <input id="login" class="block mt-1 w-full" type="text" name="login" required />
                </div>
                <div class="mb-4">
                    <label for="password">{{ __('Password') }}</label>
                    <input id="password" class="block mt-1 w-full" type="password" name="password" required />
                </div>
                <button type="submit" class="ml-4">
                    {{ __('Add') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
