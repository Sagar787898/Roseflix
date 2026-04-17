<?php
// PHP logic to get stream URL dynamically
// movie_details ya series_details se aane wali Base64 URL ko decode karna
$stream_url = isset($_GET['url']) ? base64_decode($_GET['url']) : '';

if (empty($stream_url)) {
    die("<h1 style='color:white;text-align:center;padding-top:50px;font-family:sans-serif;'>Video Link Not Found!</h1>");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>StreamX | JW Player</title>
    
    <script src="https://ssl.p.jwpcdn.com/player/v/8.22.0/jwplayer.js"></script>

    <style>
        html, body {
            padding: 0;
            margin: 0;
            height: 100%;
            width: 100%;
            background-color: #000;
            overflow: hidden;
        }

        #player {
            width: 100% !important;
            height: 100% !important;
            overflow: hidden;
            background-color: #000;
        }

        /* Top Back Button Overlay */
        .player-nav {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 999;
            transition: 0.3s opacity;
        }

        .back-btn {
            background: rgba(0,0,0,0.5);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            border: 1px solid rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
        }

        /* Hide nav when player is idle */
        .jw-state-playing.jw-flag-user-inactive .player-nav {
            opacity: 0;
        }
    </style>
</head>
<body>

    <div class="player-nav">
        <a href="javascript:history.back()" class="back-btn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
        </a>
    </div>

    <div id="player"></div>

    <script>
        var jwp = jwplayer('player');
        
        jwp.setup({
            width: "100%",
            height: "100%",
            // PHP se dynamic URL yahan aayegi
            file: "<?= $stream_url ?>",
            
            // JW Player License Key
            key: "cLGMn8T20tGvW+0eXPhq4NNmLB57TrscPjd1IyJF84o=",
            
            // Dynamic Sharing
            sharing: {
                heading: "Share this video",
                link: window.location.href,
                sites: ["facebook", "twitter", "whatsapp", "telegram", "email"]
            },
            
            primary: "html5",
            autostart: true,
            cast: {}, // Chromecast support
            
            // Custom Theme (StreamX Style)
            skin: {
                name: "glow",
                active: "#e50914", // Netflix Red
                inactive: "#ffffff",
                background: "#000000"
            },

            // MKV fix for browsers
            type: "mp4" 
        });

        // Error handling agar link dead ho ya browser support na kare
        jwp.on('error', function(e) {
            console.log("Player Error: " + e.message);
            // Agar playback fail ho toh alternative handling
        });

        // Smart UI: Auto-hide mouse and back button
        jwp.on('userInactive', function() {
            document.body.style.cursor = 'none';
        });
        jwp.on('userActive', function() {
            document.body.style.cursor = 'default';
        });
    </script>
</body>
</html>
