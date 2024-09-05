<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebSocket Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
        input {
            padding: 10px;
            font-size: 16px;
            width: 300px;
            margin-top: 20px;
        }
        #status {
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <h1>WebSocket Test</h1>

    <button id="connectBtn">Connect to WebSocket</button>
    <br>

    <input type="text" id="messageInput" placeholder="Enter a message">
    <br>
    <button id="sendBtn" disabled>Send Message</button>

    <div id="status"></div>

    <script>
        var ws;
        var connectBtn = document.getElementById('connectBtn');
        var sendBtn = document.getElementById('sendBtn');
        var messageInput = document.getElementById('messageInput');
        var status = document.getElementById('status');

        connectBtn.addEventListener('click', function() {
            ws = new WebSocket('ws://localhost:8080');

            ws.onopen = function () {
                status.innerHTML = 'Connected to WebSocket server';
                connectBtn.disabled = true;
                sendBtn.disabled = false;
                console.log('Connected to WebSocket server');
                @inject('userToken', 'App\Models\UserToken')
                var token = `{!!$userToken->generateToken()!!}`;  // You will get this from the backend

                ws.send(JSON.stringify({
                    login: token
                }));
            };

            ws.onmessage = function (event) {
                console.log('Message from server:', event.data);
                status.innerHTML += '<br>Message from server: ' + event.data;
            };

            ws.onclose = function () {
                console.log('WebSocket connection closed');
                status.innerHTML = 'WebSocket connection closed';
                connectBtn.disabled = false;
                sendBtn.disabled = true;
            };
        });

        sendBtn.addEventListener('click', function() {
            var message = messageInput.value;
            if (message !== '') {
                ws.send(message);
                console.log('Message sent:', message);
                status.innerHTML += '<br>Message sent: ' + message;
                messageInput.value = '';
            }
        });
    </script>

</body>
</html>
