<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Case openings') }} > {{ __('Edit') }} > {{ $opening->name  }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4 p-4">
            <a href="{{ route('case_openings.rewards.create', [ 'case_opening' => $opening->id ])  }}" class="font-bold text-sm hover:text-gray-700"><i class="fa fa-plus"></i> Create reward</a>
            <div class="py-2">
            <hr/>
            @foreach ($opening->rewards as $reward)
                <div class="p-2 hover:bg-gray-100">
                    <div class="float-right">
                        <a class="font-medium mr-1 hover:text-gray-600" href="{{ route('case_openings.rewards.edit', [ 'reward' => $reward->id ]) }}">
                            <i class="fa fa-fw fa-edit"></i>
                        </a>
                        <a class="font-medium hover:text-gray-600" href="{{ route('case_openings.rewards.toggle_pause', [ 'reward' => $reward->id, 'state' => $reward->is_active ]) }}">
                            <i class="fa fa-fw @if ($reward->is_active) fa-pause @else fa-play @endif"></i>
                        </a>
                    </div>
                    <a class="font-medium @if (!$reward->is_active) text-gray-300 @endif" href="{{ route('case_openings.rewards.edit', [ 'reward' => $reward->id ]) }}">
                        @if ($reward->is_active)
                            <span class="text-xs font-mono text-gray-500">{{ $reward->chance }}</span>
                        @else
                            <span class="text-xs font-mono">paused</span>
                        @endif
                        <i class="fa fa-gift"></i>
                        {{ $reward->name }}
                    </a>
                </div>
                <hr/>
            @endforeach
            </div>
        </div>
    </div>
    <hr/>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <x-input-label for="obs_browser_source" :value="__('OBS browser source')" />
        <x-text-input id="obs_browser_source" type="text" class="mt-1 block w-full text-gray-500" :value="route('obs.case_openings.show', [ 'view_key' => $opening->view_key ])" readonly />
    </div>
    <hr/>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <h3 class="font-bold">Settings</h3>
        <form method="post" action="{{ route('case_openings.update', [ 'case_opening' => $opening->id ]) }}" class="mt-6 space-y-6">
            @csrf
            @method('patch')

            @include('case_opening.partials.form')

            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Save') }}</x-primary-button>
                @if (session('message'))
                    <div class="text-green-700 text-sm">
                        {{ session('message') }}
                    </div>
                @endif
            </div>
        </form>
    </div>
</x-app-layout>
