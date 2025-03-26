<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot Layout</title>
    <!-- Add Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Chatbot Container */
        .chatbot-container {
            width: 350px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Chatbot Header */
        .chatbot-header {
            background: #4EA685;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 1.2rem;
            font-weight: bold;
        }

        /* Chatbot Messages Area */
        .chatbot-messages {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            background: #f9f9f9;
            border-bottom: 1px solid #ddd;
        }

        /* Individual Message Styles */
        .message {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
        }

        .message.user {
            justify-content: flex-end;
        }

        .message.bot {
            justify-content: flex-start;
        }

        .message .text {
            max-width: 70%;
            padding: 10px;
            border-radius: 10px;
            font-size: 0.9rem;
        }

        .message.user .text {
            background: #4EA685;
            color: white;
        }

        .message.bot .text {
            background: #e0e0e0;
            color: #333;
        }

        /* Chatbot Input Area */
        .chatbot-input {
            display: flex;
            padding: 10px;
            background: #fff;
            border-top: 1px solid #ddd;
        }

        .chatbot-input input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
        }

        .chatbot-input button {
            background: #4EA685;
            color: white;
            border: none;
            padding: 10px 15px;
            margin-left: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .chatbot-input button:hover {
            background: #45a049;
        }
    </style>
</head>

<body>
    <!-- Chatbot Container -->
    <div class="chatbot-container">
        <!-- Chatbot Header -->
        <div class="chatbot-header">
            <i class="fas fa-robot"></i> AI Health Assistant
        </div>

        <!-- Chatbot Messages Area -->
        <div class="chatbot-messages">
            <!-- Example Bot Message -->
            <div class="message bot">
                <div class="text">
                    Hello! How can I assist you today?
                </div>
            </div>

            <!-- Example User Message -->
            <div class="message user">
                <div class="text">
                    Hi, I need help with my diet plan.
                </div>
            </div>
        </div>

        <!-- Chatbot Input Area -->
        <div class="chatbot-input">
            <input type="text" placeholder="Type your message..." id="chat-input">
            <button id="send-btn"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>

    <script>
        // JavaScript for Chatbot Functionality
        const chatInput = document.getElementById('chat-input');
        const sendBtn = document.getElementById('send-btn');
        const chatMessages = document.querySelector('.chatbot-messages');

        // Function to add a user message to the chat
        function addUserMessage(message) {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message', 'user');
            messageDiv.innerHTML = `
                <div class="text">${message}</div>
            `;
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight; // Scroll to the bottom
        }

        // Function to add a bot message to the chat
        function addBotMessage(message) {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message', 'bot');
            messageDiv.innerHTML = `
                <div class="text">${message}</div>
            `;
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight; // Scroll to the bottom
        }

        // Send button click event
        sendBtn.addEventListener('click', () => {
            const userMessage = chatInput.value.trim();
            if (userMessage) {
                addUserMessage(userMessage);
                chatInput.value = ''; // Clear input field

                // Simulate bot response (you can replace this with actual API calls)
                setTimeout(() => {
                    addBotMessage("I'm sorry, I can't process that right now. Please try again later.");
                }, 1000);
            }
        });

        // Allow pressing Enter to send a message
        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendBtn.click();
            }
        });
    </script>
</body>

</html>