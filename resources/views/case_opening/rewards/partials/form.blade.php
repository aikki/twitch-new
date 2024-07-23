<div>
    <x-input-label for="name" :value="__('Name')" />
    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $reward->name ?? '')" required autofocus autocomplete="off" />
    <x-input-error class="mt-2" :messages="$errors->get('name')" />
</div>

<div>
    <x-input-label for="weight" :value="__('Weight')" />
    <x-text-input id="weight" name="weight" type="number" class="mt-1 block w-full" :value="old('weight', $reward->weight ?? '')" required autocomplete="off" />
    <x-input-error class="mt-2" :messages="$errors->get('weight')" />
</div>

<div>
    <x-input-label for="image_url" :value="__('Image URL')" />
    <x-text-input id="image_url" name="image_url" type="url" class="mt-1 block w-full" :value="old('image_url', $reward->image_url ?? '')" autocomplete="off" />
    <x-input-error class="mt-2" :messages="$errors->get('image_url')" />
</div>

<div>
    <x-input-label for="color" :value="__('Background color')" />
    <x-text-input id="color" name="color" type="color" class="mt-1 block w-full" :value="old('color', $reward->color ?? '')" autocomplete="off" />
    <x-input-error class="mt-2" :messages="$errors->get('color')" />
</div>

<div>
    <x-input-label for="streamerbot_action_id" :value="__('Streamerbot action id')" />
    <x-text-input id="streamerbot_action_id" name="streamerbot_action_id" type="text" class="mt-1 block w-full" :value="old('streamerbot_action_id', $reward->streamerbot_action_id ?? '')" autocomplete="off" />
    <x-input-error class="mt-2" :messages="$errors->get('streamerbot_action_id')" />
</div>
