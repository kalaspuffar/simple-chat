<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple chat.</title>
</head>
<body>
    <div id="chat"></div>

    <textarea id="input" rows="4" cols="40"></textarea>
    <button id="send">Send</button>

    <div id="communication">[{"role":"system","content":"You are a helpful assistant that tries to educate the user about computer hardware"}]</div>

    <script>
        const communicationEl = document.getElementById('communication');
        const chatEl = document.getElementById('chat');
        const inputEl = document.getElementById('input');
        const sendEl = document.getElementById('send');
        
        sendEl.addEventListener("click", function(e) {
            communicationEl.style.border = '2px solid red';

            var messages = JSON.parse(communicationEl.innerHTML);
            messages.push({
                "role": "user",
                "content": inputEl.value
            })

            const data = {
                'messages': messages,
            }
            fetch("chat.php", {
                method: 'POST',
                body: JSON.stringify(data)
            }).then((res) => res.json()
            ).then((body) => {
                messages.push({
                    "role": "system",
                    "content": body.choices[0].message.content
                });
                communicationEl.innerHTML = JSON.stringify(messages);
                communicationEl.style.border = '';
                displayChat();
            });
        });
        displayChat();

        function displayChat() {
            var messages = JSON.parse(communicationEl.innerHTML);

            var output = document.createElement('article');

            for (let i = 0; i < messages.length; i++) {
                var speech = document.createElement('p');
                var speaker = '<span style="color: blue">User:</span> ';
                if (messages[i].role == 'system') {
                    speaker = '<span style="color: red">Assistant:</span> ';
                }
                
                speech.innerHTML = speaker + messages[i].content.replaceAll('\n', '<br>');
                output.append(speech);
            }
            chatEl.innerHTML = '';
            chatEl.append(output);
        }
    </script>
</body>
</html>