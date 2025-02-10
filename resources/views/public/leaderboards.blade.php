<!DOCTYPE html>
<html>
<head>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=open-sans:400,600|reddit-mono:300" rel="stylesheet" />
    <title>Twitch Leaderboards</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Open Sans', sans-serif;
            font-weight: 400;
            background-color: #18181b;
            color: #e7e7eb
        }
        .container {
            display: flex;
            flex-wrap: wrap;
        }
        .leaderboard {
            /*display: ;*/
            width: 460px;
            margin: 20px;
            padding: 20px;
            border: 1px solid #555;
            border-radius: 15px;
            position: relative;
        }
        .leaderboard .title {
            position: absolute;
            font-size: 26px;
            font-variant: all-small-caps;
            top: -22px;
            background-color: #18181b;
            font-weight: 600;
        }
        .leaderboard .entry {
            margin-top: 3px;
        }
        .leaderboard .entry .place {
            color: #b0b0bc;
            font-family: 'Reddit Mono', monospace;
            margin-right: 5px;
        }
        .leaderboard .entry.podium {
            font-size: 26px;
            font-weight: bold;
        }
        .leaderboard .entry.podium .place {
            margin-left: 5px;
            margin-right: -3px;

        }
        .leaderboard .entry.place-1 {
            color: #c8b273;
        }
        .leaderboard .entry.place-2 {
            color: #d6d6d6;
        }
        .leaderboard .entry.place-3 {
            color: #977547;
        }
        .leaderboard .entry .value {
            font-family: 'Reddit Mono', monospace;
            margin-right: 5px;
            float: right;
        }
        .footnote {
            color: #6d6d6d;
            font-size: 12px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        @forelse($leaderboards as $leaderboard)
            <div class="leaderboard">
                <div class="title">{{ $leaderboard['title'] }}</div>
                @foreach($leaderboard['data'] as $entry)
                    <div class="entry @if($loop->iteration <= 3)podium place-{{ $loop->iteration }}@endif">
                        <span class="place">
                            @if($loop->iteration > 3 && $loop->iteration < 10)&nbsp;@endif{{ $loop->iteration }}
                        </span>
                        {{ $entry['name'] }}
                        <span class="value">{{ $entry['value'] }}</span>
                    </div>
                @endforeach
                @if(!empty($leaderboard['footnote']))
                    <div class="footnote">{{ $leaderboard['footnote'] }}</div>
                @endif
            </div>
        @empty
            <p>{{ __('This user does not have any public leaderboards :(')  }}</p>
        @endforelse
    </div>
</body>
</html>
