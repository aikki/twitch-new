<!DOCTYPE html>
<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=open-sans:700" rel="stylesheet" />
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Open Sans', sans-serif;
            font-weight: 700;
        }
        .reward {
            margin-left: 3px;
            margin-top: 3px;
            width: 150px;
            height: 166px;
            background: #008bfd;
            position: relative;
            text-align: center;
            border-radius: 10px;
            /*box-shadow: 0px 0px 0px 2px rgba(66, 68, 90, 1) inset;*/
            border: 2px solid black;
        }

        /*.winner {*/
        /*    border: 2px solid hotpink;*/
        /*}*/

        .reward .image {
            margin: auto;
            padding: 4px 6px;
            width: calc(100% - 12px);
            height: 70px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            background-color: white;
        }
        .reward .image img {
            max-height: 70px;
        }
        .reward .title {
            font-size: 16px;
            position: absolute;
            bottom: 0;
            padding: 4px 6px;
            width: calc(100% - 12px);
            height: 44px;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            background-color: white;
        }
        .container {
            display: none;
            width: 996px;
            height: 176px;
            overflow: hidden;
            position: relative;
            border: 2px solid black;
            background-color: rgba(255,255,255,0.1);
            border-radius: 10px;
        }

        .rewards {
            width: 7000px;
            margin: auto;
            display: flex;
            position: absolute;
        }
        .container:before {
            content: "";
            position: absolute;
            z-index: 1;
            top: 0;
            bottom: 0;
            left: 490px;
            border-style: solid;
            border-width: 0 10px 10px 10px;
            border-color: transparent transparent black transparent;
        }

        .container:after {
            content: "";
            position: absolute;
            z-index: 1;
            top: 0;
            bottom: 0;
            left: 490px;
            border-style: solid;
            border-width: 10px 10px 0 10px;
            border-color: black transparent transparent transparent;
        }
        .container .line {
            width: 6px;
            height: 180px;
            position: absolute;
            left: 497px;
            background-color: rgba(0,0,0,0.6);
            z-index: 1;
        }

        .error {
            display: none;
            font-size: 24px;
        }
        .error .details {
            font-size: 12px;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="line"></div>
        <div class="rewards">

        </div>
    </div>
    <div class="error">
        <p class="info">{{ __('Oh no! Please tell your streamer, that there\'s something wrong with case opening!') }}</p>
        <p class="details"></p>
    </div>
<script>
    $(function() {
        /*
            OPENING
         */
        let random = function(min, max) { return Math.floor(Math.random() * (max - min + 1) ) + min; };

        let request_id = 1;

        let notify = function(data) {
            $('.container').fadeOut(500);

            console.log(data);
            if (data.case_reward_streamerbot_action_id) {
                ws.send(JSON.stringify(
                    {
                        "request": "DoAction",
                        "id": (request_id++).toString(),
                        "action": {
                            "id": data.case_reward_streamerbot_action_id
                        },
                        "args": {
                            "user_name": data.user_name,
                            "case_reward_name": data.case_reward_name
                        }
                    }
                ));
            }
        }

        let roll = function(data) {
            setTimeout(function() {
                let audio = new Audio('/audio/tada.mp3');
                audio.volume = 1;
                audio.play();
                notify(data);
            }, 8000)
            $(".reward").animate(
                { left: '-' + random(4687, 4835) + 'px' },
                {
                    duration: 8000,
                    easing: 'easeOutQuint',
                    progress: function() {
                        if ($(this).hasClass('unsound') && this.offsetLeft < 500) {
                            $(this).removeClass('unsound');
                            let audio = new Audio('/audio/tick.mp3');
                            audio.volume = 1;
                            audio.play();
                        }
                    }
                }
            );
        }

        let opening = function(data) {
            $.ajax('{{ route('obs.case_openings.redeem', ['view_key' => $opening->view_key]) }}', {
                data: JSON.stringify(data),
                contentType: 'application/json',
                type: 'POST',
                success: function (response) {
                    $('.rewards').html(response.html).css({'left': '0px'});
                    $('.container').fadeIn(1000, function () {
                        roll({...data, ...response.data});
                    });
                },
                error: function(response) {
                    if (response.responseJSON && response.responseJSON.error) {
                        $('.error .details').text(response.responseJSON.error);
                    } else {
                        $('.error .details').text();
                    }
                    $('.error').fadeIn(500, function () {
                        setTimeout(function() {
                            $('.error').fadeOut(500);
                        }, 5000);
                    });
                }
            });
        }

        /*
            WEBSOCKET
         */
        let ws;

        function connect() {
            ws = new WebSocket("ws://localhost:8080/");
            ws.onclose = function() {
                console.log('hmm');
                setTimeout(connect, 2000);
            };
            ws.onopen = function() {
                ws.send(JSON.stringify(
                    {
                        "request": "Subscribe",
                        "id": (request_id++).toString(),
                        "events": {
                            "Twitch": [
                                "RewardRedemption"
                            ],
                            "General": [
                                "Custom"
                            ]
                        },
                    }
                ));
                ws.onmessage = function (message) {
                    const json = JSON.parse(message.data);
                    if (json.event && (json.event.source === 'Twitch' && json.event.type === 'RewardRedemption' || json.event.source === 'General' && json.event.type === 'Custom')) {
                        if (json.data.reward.id === '{{ $opening->streamerbot_reward_id }}') {
                            opening(json.data);
                        }
                    }
                };
            }
        }

        connect();
    });
</script>
</body>
</html>
