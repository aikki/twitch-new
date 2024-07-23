@foreach($rewards as $key => $reward)
    <div @class([ 'reward', 'winner' => $key === 24 ]) @style([ "background-color: {$reward->color}" => $reward->color ])>
        <div class="image">
            @if ($reward->image_url)
                <img src="http://localhost/images/bigpaws.100.png" />
            @endif
        </div>
        <div class="title">{{ $reward->name }}</div>
    </div>
@endforeach
