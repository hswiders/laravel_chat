<!-- resources/views/chat.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Realtime Chat Application</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.5/css/bulma.min.css">
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        // Initialize Pusher
        var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            forceTLS: true
        });

        // Subscribe to chat-channel
        var channel = pusher.subscribe('chat-channel');

        // Bind event listener to send-message event
        channel.bind('send-message', function(data) {
            appendMessage(data);
        });

        // Append new message to the chat history
        function appendMessage(data) {
            var chatHistory = document.getElementById('chat-history');
            var message = '<div class="box">' +
                            '<article class="media">' +
                                '<div class="media-content">' +
                                    '<div class="content">' +
                                        '<p>' +
                                            '<strong>' + data.user + '</strong>' +
                                            '<br>' + data.message +
                                            '<br>' +
                                            '<small>' + data.created_at + '</small>' +
                                        '</p>' +
                                    '</div>' +
                                '</div>' +
                            '</article>' +
                        '</div>';

            chatHistory.innerHTML += message;
        }

        // Send message using AJAX
        function sendMessage() {
            var user = document.getElementById('user').value;
            var message = document.getElementById('message').value;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route('send-message') }}', true);
            xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
            xhr.send(JSON.stringify({
                user: user,
                message: message,
                _token : "{{ csrf_token() }}"
            }));

            document.getElementById('message').value = '';
        }
    </script>
</head>
<body>
    <section class="section">
        <div class="container">
            <div class="columns">
                <div class="column is-4">
                    <div class="box">
                        <label class="label">Name:</label>
                        <div class="control">
                            <input class="input" type="text" placeholder="Enter your name" id="user">
                        </div>
                    </div>

                    <div class="box">
                        <label class="label">Message:</label>
                        <div class="control">
                            <textarea class="textarea" placeholder="Enter your message" id="message"></textarea>
                        </div>
                    </div>

                    <button class="button is-primary" onclick="sendMessage()">Send</button>
                </div>
                <div class="column is-8">
                    <div class="box" id="chat-history">
                        @foreach ($chats as $chat)
                            <div class="box">
                                <article class="media">
                                    <div class="media-content">
                                        <div class="content">
                                            <p>
                                                <strong>{{ $chat->user }}</strong>
                                                <br>{{ $chat->message }}
                                                <br><small>{{ $chat->created_at }}</small>
                                            </p>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
   
</section>
</body>
</html>