<!DOCTYPE html>
<html>
<head>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=open-sans:400,600|reddit-mono:300" rel="stylesheet" />
    <title>Case opening rewards</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Open Sans', sans-serif;
            font-weight: 400;
            background-color: #18181b;
            color: #e7e7eb
        }
        .opening {
            display: inline-block;
            width: 400px;
            margin: 20px;
            padding: 20px;
            border: 1px solid #555;
            border-radius: 15px;
            position: relative;
        }
        .opening .title {
            position: absolute;
            font-size: 32px;
            font-variant: all-small-caps;
            top: -25px;
            background-color: #18181b;
            font-weight: 600;
        }
        .opening .reward {
            margin-top: 3px;
        }
        .opening .reward .chance {
            color: #b0b0bc;
            font-family: 'Reddit Mono', monospace;
            margin-right: 5px;
        }
    </style>
</head>
<body>
@forelse($openings as $opening)
    <div class="opening">
        <div class="title">{{ $opening->name }}</div>
        @foreach($opening->active_rewards as $reward)
            <div class="reward">
                <span class="chance">{{ $reward->chance }}</span>
                {{ $reward->name }}
            </div>
        @endforeach
    </div>
@empty
    <p>{{ __('This user does not have any public openings :(')  }}</p>
@endforelse
</body>
</html>
