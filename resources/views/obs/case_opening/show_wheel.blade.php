<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=open-sans:700" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
        }

        canvas {
            border: 6px solid black;
            border-radius: 50%;
            transform: rotate(-90deg);
        }

        /*.border {*/
        /*    border: 6px dotted rgba(255,255,255,0.3);*/
        /*    border-radius: 50%;*/
        /*    width: 400px;*/
        /*    height: 400px;*/
        /*    position: absolute;*/
        /*    z-index: 5;*/
        /*}*/

        #pointer {
            width: 0;
            height: 0;
            border-left: 10px solid transparent;
            border-right: 10px solid transparent;
            border-top: 30px solid black;
            position: absolute;
            left: 197px;
            top: 2px;
            z-index: 10;
        }

        .error {
            display: none;
            font-size: 14px;
            width: 370px;
            left: 20px;
            position: absolute;
            top: 160px;
            text-align: center;
            text-shadow: 1px 1px 1px red, 1px -1px 1px red, -1px 1px 1px red, -1px -1px 1px red;
            color: white;
        }
        .error .details {
            font-size: 12px;
        }

        .container {
            display: none;
        }
    </style>
</head>
<body>

    <div class="container">
        <div id="pointer"></div>
        <div class="border"></div>
        <canvas id="wheel" width="400" height="400"></canvas>
    </div>

    <div class="error">
        <p class="info">{{ __('Oh no! Please tell your streamer, that there\'s something wrong with case opening!') }}</p>
        <p class="details"></p>
    </div>



    <script>
        const canvas = document.getElementById('wheel');
        const ctx = canvas.getContext('2d');
        const radius = canvas.width / 2;

        let segments = [];
        let segmentAngles = [];

        const totalAngle = 2 * Math.PI;

        function prepareSegments(s) {
            let start = 0;
            segments = s;
            segmentAngles = [];

            const angles = segments.map(s => s.share * totalAngle);

            for (let angle of angles) {
                segmentAngles.push({ startAngle: start, endAngle: start + angle });
                start += angle;
            }
        }

        let currentRotation = 0;
        let isSpinning = false;

        function drawWheel(rotation = 0) {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.save();
            ctx.translate(radius, radius);
            ctx.rotate(rotation);

            segments.forEach((segment, i) => {
                const start = segmentAngles[i].startAngle;
                const end = segmentAngles[i].endAngle;

                // Segment
                ctx.beginPath();
                ctx.moveTo(0, 0);
                ctx.arc(0, 0, radius, start, end);
                ctx.fillStyle = segment.color;
                ctx.fill();
                ctx.strokeStyle = "#fff";
                ctx.lineWidth = 2;
                ctx.stroke();

                // Tekst
                ctx.save();
                const midAngle = (start + end) / 2;
                ctx.rotate(midAngle);
                ctx.translate(radius * 0.65, 0);
                ctx.rotate(Math.PI / 2);
                ctx.fillStyle = "#fff";
                ctx.font = "bold 14px Open Sans";
                ctx.textAlign = "center";
                ctx.fillText(segment.label, 0, 0);
                ctx.restore();
            });

            ctx.restore();
        }

        drawWheel();

        function spinTo(index, data) {
            if (isSpinning) return;
            isSpinning = true;

            currentRotation = 0;

            const spins = 5;
            const target = segmentAngles[index];
            const midAngle = (target.startAngle + target.endAngle) / 2;
            const finalAngle = totalAngle - midAngle; // koło się kręci, ale pointer zostaje w górze

            const totalRotation = spins * totalAngle + finalAngle;
            const duration = 5000;
            const startTime = performance.now();
            let last = 0;
            function animate(time) {
                const elapsed = time - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const easeOut = 1 - Math.pow(1 - progress, 4); // cubic ease out

                if (last + 5 < easeOut * 100 && progress < 1) {
                    last = easeOut * 100;
                    let audio = new Audio('/audio/tick.mp3');
                    audio.volume = 1;
                    audio.play();
                }

                const angle = currentRotation + totalRotation * easeOut;
                drawWheel(angle);

                if (progress < 1) {
                    requestAnimationFrame(animate);
                } else {
                    currentRotation += totalRotation;
                    isSpinning = false;

                    let audio = new Audio('/audio/tada.mp3');
                    audio.volume = 1;
                    audio.play();
                    notify(data);
                }
            }

            requestAnimationFrame(animate);
        }

        let random = function(min, max) { return Math.floor(Math.random() * (max - min + 1) ) + min; };
        let request_id = 1;

        let notify = function(data) {
            $('.container').fadeOut(2000);

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

        let opening = function(data) {
            $.ajax('{{ route('obs.case_openings.redeem', ['view_key' => $opening->view_key]) }}', {
                data: JSON.stringify(data),
                contentType: 'application/json',
                type: 'POST',
                success: function (response) {
                    prepareSegments(response.segments);
                    drawWheel();
                    $('.container').fadeIn(1000, function () {
                        spinTo(response.winner, response.data);
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
    </script>
</body>
</html>
