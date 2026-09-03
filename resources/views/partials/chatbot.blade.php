<!-- Chatbot Container (Opens at the exact original position above floating button) -->
<div id="chatbot-container" 
     style="position: fixed; bottom: 100px; right: 24px; width: 345px; height: 470px; background: #ffffff; border: 1px solid rgba(141, 133, 236, 0.25); border-radius: 20px; box-shadow: 0 12px 35px rgba(0,0,0,0.2); display: none; flex-direction: column; z-index: 35; overflow: hidden; font-family: inherit; transition: all 0.3s ease;">
    
    <!-- Chat Header -->
    <div style="background: linear-gradient(135deg, #8D85EC, #6C63FF); color: white; padding: 12px 16px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <div style="display: flex; align-items: center; gap: 10px;">
            <!-- Cute Bot Avatar -->
            <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.25); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; position: relative;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: white;">
                    <rect x="3" y="11" width="18" height="10" rx="3"></rect>
                    <circle cx="8.5" cy="15.5" r="1.5" fill="currentColor"></circle>
                    <circle cx="15.5" cy="15.5" r="1.5" fill="currentColor"></circle>
                    <path d="M9 7V4M15 7V4"></path>
                    <path d="M9 18h6"></path>
                </svg>
                <span style="position: absolute; bottom: 0; right: 0; width: 8px; height: 8px; background: #10B981; border: 1.5px solid #6C63FF; border-radius: 50%;"></span>
            </div>
            <div>
                <div style="font-weight: bold; font-size: 15px; line-height: 1.2;">EventBot <span style="font-size: 10px; background: rgba(255,255,255,0.25); padding: 1px 5px; border-radius: 6px; margin-left: 3px;">AI</span></div>
                <div style="font-size: 11px; opacity: 0.9; display: flex; align-items: center; gap: 4px;">
                    <span style="width: 5px; height: 5px; background: #34D399; border-radius: 50%; display: inline-block;"></span>
                    Online & Ready
                </div>
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 6px;">
            <!-- Reset History -->
            <button id="clearChat" title="Reset chat" style="background: none; border: none; color: white; opacity: 0.8; font-size: 14px; cursor: pointer; padding: 4px; border-radius: 6px; display: flex; align-items: center;">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
            <!-- Close Button -->
            <button id="closeChat" title="Close" style="background: none; border: none; color: white; font-size: 22px; cursor: pointer; line-height: 1; padding: 0 4px;">×</button>
        </div>
    </div>

    <!-- Chat Messages Stream -->
    <div id="chat-content" 
         style="flex: 1; overflow-y: auto; padding: 14px; font-size: 13.5px; color: #333; display: flex; flex-direction: column; gap: 10px; background: #FAFAFC;">
        
        <!-- Welcome Card -->
        <div style="background: white; border: 1px solid #EAEAEA; border-radius: 14px; padding: 12px 14px; box-shadow: 0 2px 6px rgba(0,0,0,0.03);">
            <p style="margin: 0 0 6px 0; font-weight: 600; color: #2D3748;">Hello 👋! How can I help you today?</p>
            <p style="margin: 0; font-size: 12.5px; color: #6B7280; line-height: 1.4;">I can assist you with events, venues, bookings, and payments across Eventify.</p>
        </div>

        <!-- Quick Starter Prompt Chips -->
        <div id="quick-prompts" style="display: flex; flex-wrap: wrap; gap: 6px; margin-top: 2px;">
            <button type="button" onclick="sendQuickPrompt('What upcoming events are available?')" class="quick-chip">🎉 Upcoming Events</button>
            <button type="button" onclick="sendQuickPrompt('Show me available venues and prices')" class="quick-chip">🏰 Find Venues</button>
            <button type="button" onclick="sendQuickPrompt('How does Khalti payment work?')" class="quick-chip">💳 Khalti Payment</button>
            <button type="button" onclick="sendQuickPrompt('How do I book tickets or a venue?')" class="quick-chip">🎟️ How to Book</button>
            <button type="button" onclick="sendQuickPrompt('What are my bookings?')" class="quick-chip">👤 My Bookings</button>
        </div>

    </div>

    <!-- Typing Indicator (Hidden by default) -->
    <div id="typing-indicator" style="display: none; padding: 6px 16px; font-size: 12px; color: #8D85EC; background: #FAFAFC; align-items: center; gap: 6px;">
        <span class="typing-dot"></span>
        <span class="typing-dot" style="animation-delay: 0.2s;"></span>
        <span class="typing-dot" style="animation-delay: 0.4s;"></span>
        <span style="font-style: italic; font-size: 11px; margin-left: 2px; color: #718096;">EventBot is thinking...</span>
    </div>

    <!-- Input Box -->
    <div style="display: flex; border-top: 1px solid #EAEAEA; background: white; padding: 8px 10px; align-items: center; gap: 6px;">
        <input id="chat-input" 
               type="text" 
               placeholder="Type a message..." 
               autocomplete="off"
               style="flex: 1; border: 1px solid #E5E7EB; border-radius: 20px; padding: 8px 14px; outline: none; font-size: 13px; transition: border-color 0.2s;"
               onfocus="this.style.borderColor='#8D85EC'"
               onblur="this.style.borderColor='#E5E7EB'">
        
        <button id="sendChat" 
                type="button"
                style="background: linear-gradient(135deg, #8D85EC, #6C63FF); color: white; border: none; border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 3px 8px rgba(108,99,255,0.3); transition: transform 0.2s; flex-shrink: 0;">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="transform: rotate(45deg); margin-left: -2px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
            </svg>
        </button>
    </div>
</div>

<!-- Floating Chat Button (At the exact original position with smooth floating animation) -->
<button id="openChat" 
        aria-label="Open EventBot"
        style="position: fixed; bottom: 24px; right: 24px; background: linear-gradient(135deg, #8D85EC, #6C63FF); color: white; border: none; border-radius: 50%; width: 68px; height: 68px; cursor: pointer; box-shadow: 0 8px 25px rgba(108, 99, 255, 0.45); z-index: 30; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; animation: floatingBot 3s ease-in-out infinite;">
    
    <!-- Modern AI Bot Icon inside button -->
    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));">
        <rect x="3" y="11" width="18" height="10" rx="3"></rect>
        <circle cx="8.5" cy="15.5" r="1.5" fill="currentColor"></circle>
        <circle cx="15.5" cy="15.5" r="1.5" fill="currentColor"></circle>
        <path d="M9 7V4M15 7V4"></path>
        <path d="M9 18h6"></path>
    </svg>

    <!-- Glowing notification dot -->
    <span style="position: absolute; top: 2px; right: 2px; width: 12px; height: 12px; background: #10B981; border: 2px solid white; border-radius: 50%;"></span>
</button>

<style>
/* Gentle floating animation for the button */
@keyframes floatingBot {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-8px); }
    100% { transform: translateY(0px); }
}

#openChat:hover {
    transform: scale(1.1) translateY(-4px) !important;
    box-shadow: 0 12px 30px rgba(141, 133, 236, 0.7) !important;
}

/* Quick prompt chips */
.quick-chip {
    background: #F3F0FF;
    color: #6C63FF;
    border: 1px solid #E9D5FF;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11.5px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}
.quick-chip:hover {
    background: #8D85EC;
    color: white;
    border-color: #8D85EC;
    transform: translateY(-1px);
}

/* Message bubbles */
.chat-bubble-wrap {
    display: flex;
    flex-direction: column;
    max-width: 82%;
    animation: bubbleFade 0.2s ease-out;
}

@keyframes bubbleFade {
    from { opacity: 0; transform: translateY(4px); }
    to { opacity: 1; transform: translateY(0); }
}

.user-bubble {
    align-self: flex-end;
    background: linear-gradient(135deg, #8D85EC, #7C73E6);
    color: white;
    padding: 9px 13px;
    border-radius: 14px 14px 2px 14px;
    font-size: 13px;
    line-height: 1.4;
    word-wrap: break-word;
    box-shadow: 0 2px 6px rgba(141, 133, 236, 0.25);
}

.bot-bubble {
    align-self: flex-start;
    background: white;
    color: #2D3748;
    padding: 10px 13px;
    border-radius: 14px 14px 14px 2px;
    font-size: 13px;
    line-height: 1.45;
    word-wrap: break-word;
    border: 1px solid #EAEAEA;
    box-shadow: 0 2px 6px rgba(0,0,0,0.03);
}

.bot-bubble p { margin: 0 0 6px 0; }
.bot-bubble p:last-child { margin-bottom: 0; }
.bot-bubble ul, .bot-bubble ol { margin: 4px 0 6px 0; padding-left: 18px; }
.bot-bubble li { margin-bottom: 3px; }
.bot-bubble strong { color: #1A202C; font-weight: 600; }
.bot-bubble a { color: #6C63FF; text-decoration: underline; font-weight: 600; }
.bot-bubble a:hover { color: #4F46E5; }

.message-time {
    font-size: 10px;
    color: #9CA3AF;
    margin-top: 3px;
}
.user-wrap .message-time { align-self: flex-end; }
.bot-wrap .message-time { align-self: flex-start; }

/* Typing animation dots */
.typing-dot {
    width: 6px;
    height: 6px;
    background: #8D85EC;
    border-radius: 50%;
    display: inline-block;
    animation: typingBounce 1.2s infinite ease-in-out;
}
@keyframes typingBounce {
    0%, 80%, 100% { transform: scale(0); }
    40% { transform: scale(1); }
}

/* Custom scrollbar */
#chat-content::-webkit-scrollbar {
    width: 5px;
}
#chat-content::-webkit-scrollbar-thumb {
    background: #DDD6FE;
    border-radius: 10px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const openChatBtn = document.getElementById('openChat');
    const closeChatBtn = document.getElementById('closeChat');
    const clearChatBtn = document.getElementById('clearChat');
    const chatbotContainer = document.getElementById('chatbot-container');
    const chatInput = document.getElementById('chat-input');
    const chatContent = document.getElementById('chat-content');
    const typingIndicator = document.getElementById('typing-indicator');
    const sendBtn = document.getElementById('sendChat');

    let isThinking = false;

    openChatBtn.addEventListener('click', () => {
        chatbotContainer.style.display = 'flex';
        openChatBtn.style.display = 'none';
        setTimeout(() => chatInput.focus(), 150);
        scrollToBottom();
    });

    closeChatBtn.addEventListener('click', () => {
        chatbotContainer.style.display = 'none';
        openChatBtn.style.display = 'flex';
    });

    clearChatBtn.addEventListener('click', () => {
        if (confirm("Reset conversation?")) {
            fetch("{{ route('chatbot.clear') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            })
            .then(res => res.json())
            .then(() => {
                chatContent.innerHTML = `
                    <div style="background: white; border: 1px solid #EAEAEA; border-radius: 14px; padding: 12px 14px; box-shadow: 0 2px 6px rgba(0,0,0,0.03);">
                        <p style="margin: 0 0 6px 0; font-weight: 600; color: #2D3748;">Chat reset! How else can I help?</p>
                        <p style="margin: 0; font-size: 12.5px; color: #6B7280;">Ask anything about events, venues, bookings, or payments.</p>
                    </div>
                    <div id="quick-prompts" style="display: flex; flex-wrap: wrap; gap: 6px; margin-top: 2px;">
                        <button type="button" onclick="sendQuickPrompt('What upcoming events are available?')" class="quick-chip">🎉 Upcoming Events</button>
                        <button type="button" onclick="sendQuickPrompt('Show me available venues and prices')" class="quick-chip">🏰 Find Venues</button>
                        <button type="button" onclick="sendQuickPrompt('How do I book tickets?')" class="quick-chip">🎟️ How to Book</button>
                    </div>`;
            });
        }
    });

    window.sendQuickPrompt = function(text) {
        chatInput.value = text;
        sendMessage();
    };

    sendBtn.addEventListener('click', sendMessage);
    chatInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendMessage();
    });

    function formatTime() {
        const now = new Date();
        let hours = now.getHours();
        let minutes = now.getMinutes().toString().padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12 || 12;
        return `${hours}:${minutes} ${ampm}`;
    }

    function scrollToBottom() {
        chatContent.scrollTop = chatContent.scrollHeight;
    }

    // Markdown Parser
    function parseMarkdown(text) {
        if (!text) return '';
        let html = text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/^### (.*$)/gim, '<strong style="display:block; margin: 4px 0;">$1</strong>')
            .replace(/\*\*(.*?)\*\*/gim, '<strong>$1</strong>')
            .replace(/\*(.*?)\*/gim, '<em>$1</em>')
            .replace(/\[([^\]]+)\]\(([^)]+)\)/gim, '<a href="$2">$1</a>')
            .replace(/`([^`]+)`/gim, '<code style="background:#EDE9FE; color:#6C63FF; padding:1px 4px; border-radius:4px; font-size:12px;">$1</code>');

        const lines = html.split('\n');
        let inList = false;
        let result = [];

        for (let line of lines) {
            const trimmed = line.trim();
            if (trimmed.startsWith('• ') || trimmed.startsWith('- ') || trimmed.startsWith('* ')) {
                if (!inList) {
                    inList = true;
                    result.push('<ul>');
                }
                result.push(`<li>${trimmed.substring(2)}</li>`);
            } else if (/^\d+\.\s/.test(trimmed)) {
                if (!inList) {
                    inList = true;
                    result.push('<ol>');
                }
                result.push(`<li>${trimmed.replace(/^\d+\.\s/, '')}</li>`);
            } else {
                if (inList) {
                    inList = false;
                    result.push('</ul>');
                }
                if (trimmed.length > 0) {
                    result.push(`<p>${trimmed}</p>`);
                }
            }
        }
        if (inList) result.push('</ul>');

        return result.join('');
    }

    function sendMessage() {
        if (isThinking) return;
        const userMessage = chatInput.value.trim();
        if (!userMessage) return;

        const time = formatTime();

        // Add user bubble
        const userHtml = `
            <div class="chat-bubble-wrap user-wrap" style="align-self: flex-end;">
                <div class="user-bubble">${userMessage.replace(/</g, "&lt;").replace(/>/g, "&gt;")}</div>
                <span class="message-time">${time}</span>
            </div>`;
        chatContent.insertAdjacentHTML('beforeend', userHtml);
        chatInput.value = '';
        scrollToBottom();

        // Show typing indicator
        isThinking = true;
        typingIndicator.style.display = 'flex';
        scrollToBottom();

        fetch("{{ route('chatbot.message') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ message: userMessage })
        })
        .then(res => res.json())
        .then(data => {
            const botTime = formatTime();
            const parsed = parseMarkdown(data.reply);

            let chipsHtml = '';
            if (data.suggestions && Array.isArray(data.suggestions) && data.suggestions.length > 0) {
                const chips = data.suggestions.map(s => `
                    <button type="button" onclick="sendQuickPrompt('${s.replace(/'/g, "\\'")}')" class="quick-chip">
                        ${s}
                    </button>
                `).join('');
                chipsHtml = `<div style="display:flex; flex-wrap:wrap; gap:5px; margin-top:6px;">${chips}</div>`;
            }

            const botHtml = `
                <div class="chat-bubble-wrap bot-wrap" style="align-self: flex-start;">
                    <div class="bot-bubble">${parsed}</div>
                    ${chipsHtml}
                    <span class="message-time">${botTime}</span>
                </div>`;
            chatContent.insertAdjacentHTML('beforeend', botHtml);
            scrollToBottom();
        })
        .catch(() => {
            const errorHtml = `
                <div class="chat-bubble-wrap bot-wrap" style="align-self: flex-start;">
                    <div class="bot-bubble" style="color: #DC2626; border-color: #FCA5A5; background: #FEF2F2;">
                        Sorry, could not connect to server. Please try again.
                    </div>
                    <span class="message-time">${formatTime()}</span>
                </div>`;
            chatContent.insertAdjacentHTML('beforeend', errorHtml);
            scrollToBottom();
        })
        .finally(() => {
            isThinking = false;
            typingIndicator.style.display = 'none';
            chatInput.focus();
        });
    }
});
</script>
