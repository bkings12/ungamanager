

<div class="py-12">
    <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <form action="/device/{{ $device->id }}" method="post">
                @csrf
                @method('PUT')
                <!-- Identity field -->
                <div>
                    <x-label for="identity" :value="__('Identity')" />
                    <x-input id="identity" class="block mt-1 w-full" type="text" name="identity" :value="$device->identity" required autofocus />
                </div>
                <!-- IP Address field -->
                <div>
                    <x-label for="ip_address" :value="__('IP Address')" />
                    <x-input id="ip_address" class="block mt-1 w-full" type="text" name="ip_address" :value="$device->ip_address" required />
                </div>
                <!-- Login field -->
                <div>
                    <x-label for="login" :value="__('Login')" />
                    <x-input id="login" class="block mt-1 w-full" type="text" name="login" :value="$device->login" required />
                </div>
                <!-- Password field -->
                <div>
                    <x-label for="password" :value="__('Password')" />
                    <x-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                </div>
                <x-button class="ml-4">
                    {{ __('Update') }}
                </x-button>
            </form>
        </div>
    </div>
</div>
