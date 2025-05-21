<!DOCTYPE html>
<html>
<head>
    <title>Agora Video Call</title>
    <script src="https://download.agora.io/sdk/release/AgoraRTC_N-4.20.0.js"></script>
    <style>
        #video-container {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        .video-box {
            width: 480px;
            height: 360px;
            background-color: #000;
        }
    </style>
</head>
<body>
<h1>Agora Video Chat</h1>

<button onclick="startCall()">Start Call</button>
<button onclick="leaveCall()">Leave Call</button>

<div id="video-container"></div>

<script>
    const uid = Math.floor(Math.random() * 10000);
    const channelName = "testChannel";
    let client;
    let localTrack;
    async function startCall() {
        try {
            const res = await fetch(`/agora-token?channelName=${channelName}&uid=${uid}`);
            const { token, appId } = await res.json();
            client = AgoraRTC.createClient({ mode: "rtc", codec: "vp8" });
            await client.join(appId, channelName, token, uid);
            localTrack = await AgoraRTC.createCameraVideoTrack();
            const videoContainer = document.getElementById('video-container');
            const localVideo = document.createElement("div");
            localVideo.id = `user-${uid}`;
            localVideo.className = "video-box";
            videoContainer.appendChild(localVideo);
            localTrack.play(localVideo);
            await client.publish([localTrack]);
            console.log("Published local track");
            client.on("user-published", async (user, mediaType) => {
                await client.subscribe(user, mediaType);
                console.log("Subscribed to user:", user.uid);
                if (mediaType === "video") {
                    const remoteTrack = user.videoTrack;
                    const remoteVideo = document.createElement("div");
                    remoteVideo.id = `user-${user.uid}`;
                    remoteVideo.className = "video-box";
                    document.getElementById('video-container').appendChild(remoteVideo);
                    remoteTrack.play(remoteVideo);
                }
            });
            client.on("user-unpublished", user => {
                const remoteDiv = document.getElementById(`user-${user.uid}`);
                if (remoteDiv) remoteDiv.remove();
            });
        } catch (error) {
            console.error("Error during call setup:", error);
        }
    }
    async function leaveCall() {
        try {
            if (localTrack) {
                localTrack.stop();
                localTrack.close();
            }
            if (client) {
                await client.leave();
                console.log("Left the channel");
            }
            document.getElementById('video-container').innerHTML = '';
        } catch (error) {
            console.error("Error while leaving the call:", error);
        }
    }
</script>
</body>
</html>
