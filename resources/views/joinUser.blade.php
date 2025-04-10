<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Video Streaming</title>
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('agoraVideo/index.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <style>
          .video-container {
    display: flex;
    gap: 20px; /* space between the two sections */
    align-items: flex-start;
    flex-wrap: wrap; /* wraps on smaller screens */
  }

  #local-player {
    width: 400px;
    height: 300px;
    background-color: #000;
    border: 2px solid #ddd;
    border-radius: 10px;
  }

  #remote-playerlist {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
  }

  .player {
    width: 400px;
    height: 300px;
    background-color: #222;
    border: 2px solid #ccc;
    border-radius: 10px;
  }

  .player-name {
    color: #e41111;
    font-size: 14px;
    margin-bottom: 5px;
  }

  #player-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
  }
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        /* Meeting Link Input */
        #linkUrl {
            width: 80%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            outline: none;
            transition: 0.3s;
        }

        #linkUrl:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        /* Buttons */
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            margin: 10px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Special Buttons */
        #leave-btn {
            background-color: #dc3545;
        }

        #leave-btn:hover {
            background-color: #b02a37;
        }

        #mic-btn,
        #camera-btn,
        #rec-btn {
            background-color: #28a745;
        }

        #mic-btn:hover,
        #camera-btn:hover,
        #rec-btn:hover {
            background-color: #1e7e34;
        }

        /* Stream Wrapper */
        #stream-wrapper {
            width: 90%;
            max-width: 800px;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            text-align: center;
        }

        /* Video Streams */
        #video-streams {
            width: 100%;
            height: 400px;
            background-color: #000;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        /* Stream Controls */
        #stream-controls {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            #stream-wrapper {
                width: 95%;
            }
        }
    </style>

</head>

<body>
    @if (!session()->has('meeting'))
        <input  type="text" name="name" id="linkname" value="" placeholder="Enter your name"
            style="width: 80%; padding: 10px; border: 2px solid #ddd; border-radius: 30px; font-size: 16px; outline: none; transition: 0.3s;">
    @endif

    <input style="border-radius: 30px;" type="text" id="linkUrl" value="{{ url('joinMeeting') }}/{{ $meeting->url }}"
        placeholder="Enter or generate a meeting link">

    <button id="join-form" style="display:none;"></button>
    <div id="">
        <button style="border-radius: 30px;"  id="join-form2">Join Stream</button>
        <button style="border-radius: 30px;" id="join-forms" onclick="copyLink()">Copy Link</button>
    </div>

    <!-- Meeting Instance -->

        {{-- <div id="video-streams"></div> --}}



    {{-- <div id="local-player-name"></div>
        <div id="local-player" class="player"></div>

        <div id="remote-playerlist"></div> --}}
        <div class="video-container">
            <div>
              <div id="local-player-name"></div>
              <div id="local-player" class="player"></div>
            </div>

            <div id="remote-playerlist"></div>
          </div>
          <div id="stream-wrapper">
          <div id="stream-controls">
            <button id="leave-btn">Leave Stream</button>
            <button id="mic-btn">Mic On</button>
            <button id="camera-btn">Camera On</button>

        </div>
          </div>


    <input id="appid" type="hidden" value="{{ $meeting->app_id }}" readonly>
    <input id="token" type="hidden" value="{{ $meeting->token }}" readonly>
    <input id="channel" type="hidden" value="{{ $meeting->channel }}" readonly>
    <input id="urlId" type="hidden" value="{{ $meeting->url }}" readonly>
    <input id="event" type="hidden" value="{{ $event }}" readonly>

    <input id="user_meeting" type="hidden" value="0">
    <input id="user_permission" type="hidden" value="0">

</body>

<script src="{{ asset('agoraVideo/AgoraRTC_N-4.23.2.js') }}"></script>
<script src="{{ asset('js/agora-helper.js') }}"></script>


<script>
    // Agora client instance
    const client = AgoraRTC.createClient({
        mode: "rtc",
        codec: "vp8"
    });

    let localTracks = {
        videoTrack: null,
        audioTrack: null
    };

    let options = {
        appid: document.getElementById('appid').value.trim(),
        token: document.getElementById('token').value.trim(),
        channel: document.getElementById('channel').value.trim(),
    };

    // Join Stream
    async function AgoraFunction() {
        try {
            const canJoin = await checkHostMeeting();
            if (!canJoin) {
                console.log("You are not allowed to join the stream.");
                return;
            }

            console.log("Joining the stream...", options);

            // 🔁 Attach event listeners
            client.on("user-published",handleUserPublished);
            client.on("user-unpublished",handleUserUnpublished);


            // 🔁 Join channel and create local tracks
            [options.uid, localTracks.audioTrack, localTracks.videoTrack] = await Promise.all([
                client.join(options.appid, options.channel, options.token || null),
                AgoraRTC.createMicrophoneAudioTrack(),
                AgoraRTC.createCameraVideoTrack()
            ]);

            // 🔁 Play local video
            localTracks.videoTrack.play("local-player"); // make sure this div exists in DOM
            document.getElementById("local-player-name").innerText = `localVideo(${options.uid})`;

            // 🔁 Publish local tracks
            await client.publish(Object.values(localTracks));
            console.log("publish success");

        } catch (error) {
            console.error("Failed to join the stream:", error);
        }
    }
    document.getElementById('join-form2').addEventListener('click', async () => {

        AgoraFunction();

    });

    // Leave Stream
    document.getElementById('leave-btn').addEventListener('click', async () => {
        try {
            // Stop and close local tracks
            if (localTracks.audioTrack) {
                localTracks.audioTrack.stop();
                localTracks.audioTrack.close();
            }
            if (localTracks.videoTrack) {
                localTracks.videoTrack.stop();
                localTracks.videoTrack.close();
            }

            // Leave the Agora channel
            await client.leave();
            console.log("Successfully left the stream!");
        } catch (error) {
            console.error("Failed to leave the stream:", error);
        }
    });
</script>
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
    //pusher web socket initialization
    var notificationChannel = $('#channel').val();
    var notificationEvent = $('#event').val();
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('9a1cb6cd85f9d24d5a51', {
        cluster: 'ap2'
    });

    var channel = pusher.subscribe(notificationChannel);

    channel.bind(notificationEvent, function(data) {

        @if (session()->has('meeting'))
            //host user
            console.log('in session meeting');
            if (confirm(data.data.title)) {

                console.log('myadata', data.data.random_user);
                meetingApprove(data.data.random_user, 2);
                $('#join-form').click();
            } else {

                console.log('else block of session meeting');

            }
        @else

            //join unauthenticated user
            if (data.data.status == 2) {
                //meeting start
                console.log('mmeting  approved  by host');

                alert('Meeting has been approved by host');
                AgoraFunction();
                document.getElementById('stream-controls').style.display = 'flex';

            } else if (data.data.status == 3) {
                alert('Host has been declined your entryy');
            } else if (data.data.status == 1) {
                alert('Host has been declined your entryy123');
            } else {
                alert('Something Went wrong');
            }
        @endif

    });
</script>
<script>
    $('#join-form2').click(function() {
        // Host user
        @if (session()->has('meeting'))
            $('#join-form').click();
            document.getElementById('stream-controls').style.display = 'flex';
        @else
            // Join user
            var name = $('#linkname').val();
            if (!name || name.length < 1) {
                alert('Please enter your name');
                return;
            } else {
                saveUserName(name);
                alert('Request has been sent to host. Please wait for approval');
            }
        @endif
    });

    function saveUserName(name) {

        var url = "{{ url('saveUserName') }}";
        var random = "{{ session()->get('random_user') }}";
        var urlId = $('#urlId').val();
        $.ajax({
            url: url,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            type: 'GET',
            data: {
                name: name,
                random: random,
                url: urlId
            },
            success: function(result) {
                console.log('Name saved successfully:', result);

            },
            error: function(xhr) {
                console.error('Error saving name:', xhr.responseText);

            }
        });
    }

    function meetingApprove($random_user, type) {
        var url = "{{ url('meetingApprove') }}";
        //   var random = "{{ session()->get('random_user') }}";
        var urlId = $('#urlId').val();

        console.log('========123=========', {
            type,
            $random_user,
            url,
            urlId
        });

        $.ajax({
            url: url,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            type: 'GET',
            data: {
                name: name,
                random: $random_user,
                url: urlId,
                type: type
            },
            success: function(result) {
                console.log('Meeting approval updated:', result);
            },
            error: function(xhr) {
                console.error('Error updating meeting approval:', xhr.responseText);
            }
        });
    }

    function copyLink() {

        var urlPage = window.location.href;
        var temp = $("<input>");
        $("body").append(temp);
        temp.val(urlPage).select();
        document.execCommand("copy");
        temp.remove();
        alert('Link copied');
        $('#join-forms').text('Link copied');

    }

    async function checkHostMeeting() {
        try {
            const response = await $.ajax({
                url: "/check-host-meeting",
                method: "GET",
                dataType: "json"
            });

            return response.status; // Will return true or false
        } catch (error) {
            console.error("Error checking host meeting:", error);
            return false;
        }
    }
</script>


</html>
