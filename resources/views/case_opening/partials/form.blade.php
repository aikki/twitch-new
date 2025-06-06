<div>
    <x-input-label for="name" :value="__('Name')" />
    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $opening->name ?? '')" required autofocus autocomplete="off" />
    <x-input-error class="mt-2" :messages="$errors->get('name')" />
</div>

<div>
    <x-input-label for="streamerbot_reward_id" :value="__('Streamerbot reward id')" />
    <x-text-input id="streamerbot_reward_id" name="streamerbot_reward_id" type="text" class="mt-1 block w-full" :value="old('streamerbot_reward_id', $opening->streamerbot_reward_id ?? '')" required autocomplete="off" />
    <x-input-error class="mt-2" :messages="$errors->get('streamerbot_reward_id')" />
</div>

<div>
    <x-input-label for="type" :value="__('Type')" />
    <select id="type" name="type" required class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
        @foreach($openingTypes as $type)
            <option value="{{ $type }}" @if($type == old('type', $opening->type ?? '')) selected @endif >{{ $type }}</option>
        @endforeach
    </select>

    <x-input-error class="mt-2" :messages="$errors->get('type')" />
</div>

@if (isset($opening))
<div>
    <x-input-label for="is_public" :value="__('Public rewards chances')" class="mb-2" />
    <input type="checkbox" name="is_public" id="is_public" value="1" @if ($opening->is_public) checked @endif />
    <span class="text-gray-500 text-xs">
        {{ __('Should chances for rewards in this case be published on') }}
        <a target="_blank" class="underline" href="{{ route('public.case_openings.rewards_chances', [ 'username' => mb_strtolower($opening->user->name) ])  }}">
            {{ route('public.case_openings.rewards_chances', [ 'username' => mb_strtolower($opening->user->name) ])  }}
        </a>
    </span>
    <x-input-error class="mt-2" :messages="$errors->get('is_public')" />
</div>
@endif
