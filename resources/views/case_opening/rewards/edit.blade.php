<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('case_openings.list')  }}">{{ __('Case openings') }}</a> > <a href="{{ route('case_openings.edit', [ 'case_opening' => $reward->parent->id ])  }}">{{ $reward->parent->name  }}</a> > {{ __('Rewards')  }} > {{ __('Edit') }} > <a href="{{ route('case_openings.rewards.edit', [ 'reward' => $reward->id ])  }}">{{ $reward->name  }}</a>
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <form method="post" action="{{ route('case_openings.rewards.update', [ 'reward' => $reward ]) }}" class="mt-6 space-y-6">
            @csrf
            @method('patch')

            @include('case_opening.rewards.partials.form')

            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Save') }}</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
