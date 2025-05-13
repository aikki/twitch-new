<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Case openings') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4">
                <div class="p-6 text-gray-900">
                    <a href="{{ route('case_openings.create') }}" class="font-bold text-sm hover:text-gray-600"><i class="fa fa-plus"></i> Create new case opening</a>
                    <div class="py-2">
                        <hr/>
                        @forelse ($openings as $opening)
                            <div class="p-2 hover:bg-gray-100">
                                <div class="float-right">
                                    <a class="font-medium mr-1 hover:text-gray-600" href="{{ route('case_openings.edit', [ 'case_opening' => $opening->id ])  }}">
                                        <i class="fa fa-edit fa-fw"></i>
                                    </a>
{{--                                    <a class="font-medium text-red-600 hover:text-red-400" href="{{ route('case_openings.edit', [ 'case_opening' => $opening->id ])  }}">--}}
{{--                                        <i class="fa fa-trash fa-fw"></i>--}}
{{--                                    </a>--}}
                                </div>
                                <a class="font-medium" href="{{ route('case_openings.edit', [ 'case_opening' => $opening->id ])  }}">
                                    @if($opening->type === \App\Enum\CaseOpeningType::CASE->value)
                                        <i class="fa fa-box"></i>
                                    @elseif($opening->type === \App\Enum\CaseOpeningType::WHEEL->value)
                                        <i class="fa fa-dharmachakra"></i>
                                    @endif
                                    {{ $opening->name }}
                                </a>
                            </div>
                            <hr/>
                        @empty
                            <div class="p-2">You have no case openings defined</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
