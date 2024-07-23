<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="font-bold text-xl px-2 pb-1">
                        {{ __("Some stats") }}:
                    </h2>
                    <table class="text-lg">
                        <tr>
                            <td class="px-2">{{ __("Opened cases") }}:</td><td class="px-4 text-2xl font-bold">{{ $opened_cases }}</td>
                        </tr>
                        <tr>
                            <td class="px-2">{{ __("Points spent") }}:</td><td class="px-4 text-2xl font-bold">{{ $points_spent }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
