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
