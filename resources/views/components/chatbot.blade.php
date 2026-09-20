@php
    $chatbotConfig = \App\Models\Configuration::current();
@endphp

<!-- Widget Flutuante do Chatbot da Banca Santa Rita -->
<div id="bancaChatbotContainer" class="chatbot-container position-fixed" style="bottom: 24px; left: 24px; z-index: 1040;">
    
    <!-- Botão Disparador do Chatbot (Launcher) -->
    <button type="button" 
            id="chatbotToggleBtn" 
            class="chatbot-launcher btn rounded-pill shadow-lg d-flex align-items-center gap-2 px-3 py-2 text-white border-0"
            style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);"
            aria-label="Abrir assistente virtual da Banca Santa Rita"
            aria-expanded="false"
            aria-controls="chatbotWindow">
        <div class="position-relative d-inline-flex">
            <span class="chatbot-avatar-icon rounded-circle bg-white text-success d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-size: 1.25rem;">
                <i class="bi bi-robot"></i>
            </span>
            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle" style="background-color: #22c55e !important;" title="Online agora">
                <span class="visually-hidden">Online</span>
            </span>
        </div>
        <div class="text-start d-none d-sm-block pe-1">
            <div class="fw-bold lh-1" style="font-size: 0.88rem;">Atendente Virtual</div>
            <div class="text-white-50" style="font-size: 0.72rem;">Dúvidas & Acervo</div>
        </div>
        <i class="bi bi-chevron-up ms-1 small" id="chatbotToggleIcon"></i>
    </button>

    <!-- Janela de Conversa (Chat Window) -->
    <div id="chatbotWindow" 
         class="chatbot-window card border-0 shadow-2xl rounded-4 mt-2 overflow-hidden d-none"
         style="width: 360px; max-width: calc(100vw - 32px); height: 520px; max-height: calc(100vh - 120px); background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(16px); border: 1px solid rgba(16, 185, 129, 0.2) !important;">
        
        <!-- Cabeçalho do Chat -->
        <div class="chatbot-header p-3 text-white d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #132316 0%, #1e3322 100%);">
            <div class="d-flex align-items-center gap-2">
                <div class="position-relative">
                    <div class="rounded-circle bg-success bg-opacity-25 text-white p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-robot fs-5 text-success" style="color: #10b981 !important;"></i>
                    </div>
                    <span class="position-absolute bottom-0 end-0 p-1 bg-success rounded-circle border border-dark"></span>
                </div>
                <div>
                    <h3 class="h6 mb-0 fw-bold text-white font-heading">Rita • Assistente da Banca</h3>
                    <div class="small text-white-50 d-flex align-items-center gap-1" style="font-size: 0.75rem;">
                        <span class="d-inline-block rounded-circle bg-success" style="width: 6px; height: 6px;"></span>
                        Online agora • {{ $chatbotConfig->nome_banca }}
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-1">
                <button type="button" class="btn btn-sm btn-link text-white-50 p-1 hover-white" id="chatbotResetBtn" title="Reiniciar conversa" aria-label="Reiniciar conversa">
                    <i class="bi bi-arrow-counterclockwise fs-6"></i>
                </button>
                <button type="button" class="btn btn-sm btn-link text-white-50 p-1 hover-white" id="chatbotCloseBtn" title="Fechar chat" aria-label="Fechar chat">
                    <i class="bi bi-x-lg fs-6"></i>
                </button>
            </div>
        </div>

        <!-- Área de Mensagens (Scrollable) -->
        <div id="chatbotMessages" class="chatbot-messages p-3 flex-grow-1 overflow-y-auto d-flex flex-column gap-3" style="font-size: 0.88rem; scroll-behavior: smooth;">
            <!-- Mensagem Inicial de Boas-Vindas -->
            <div class="chatbot-msg assistant d-flex align-items-start gap-2">
                <div class="chatbot-avatar-sm rounded-circle bg-success bg-opacity-10 text-success p-1 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px;">
                    <i class="bi bi-robot small"></i>
                </div>
                <div class="chatbot-bubble p-3 rounded-4 shadow-xs" style="background-color: #f1f5f2; color: #1e2920; border-bottom-left-radius: 4px;">
                    <p class="mb-1">
                        Olá! Sou a <strong>Rita</strong>, assistente virtual da <strong>{{ $chatbotConfig->nome_banca }}</strong>! 📚
                    </p>
                    <p class="mb-2">
                        Posso consultar itens no nosso expositor (jornais, mangás, revistas, gibis), informar horários de abertura e localização ou te conectar com nosso atendente!
                    </p>
                    <div class="small fw-semibold text-muted mb-1">Como posso te ajudar agora?</div>
                </div>
            </div>

            <!-- Chips de Sugestões Rápidas -->
            <div id="chatbotSuggestions" class="d-flex flex-wrap gap-1 ps-4 ms-2">
                <button type="button" class="btn btn-sm btn-outline-success rounded-pill py-1 px-2 chatbot-chip" style="font-size: 0.75rem;" data-question="Qual o horário de funcionamento?">
                    🕒 Horário de funcionamento
                </button>
                <button type="button" class="btn btn-sm btn-outline-success rounded-pill py-1 px-2 chatbot-chip" style="font-size: 0.75rem;" data-question="Onde fica a banca?">
                    📍 Onde fica a banca?
                </button>
                <button type="button" class="btn btn-sm btn-outline-success rounded-pill py-1 px-2 chatbot-chip" style="font-size: 0.75rem;" data-question="Quais quadrinhos e mangás vocês têm?">
                    🦸 Gibis e Mangás
                </button>
                <button type="button" class="btn btn-sm btn-outline-success rounded-pill py-1 px-2 chatbot-chip" style="font-size: 0.75rem;" data-question="Como reservar um jornal ou revista?">
                    📦 Como reservar exemplares?
                </button>
                <button type="button" class="btn btn-sm btn-outline-success rounded-pill py-1 px-2 chatbot-chip" style="font-size: 0.75rem;" data-question="Quero falar com o atendente humano no WhatsApp">
                    💬 Falar com atendente
                </button>
            </div>
        </div>

        <!-- Indicador de Digitação (Typing Indicator) -->
        <div id="chatbotTyping" class="px-4 py-2 d-none align-items-center gap-2 text-muted small">
            <span class="spinner-grow spinner-grow-sm text-success" role="status" aria-hidden="true" style="width: 10px; height: 10px;"></span>
            <span>Rita está digitando...</span>
        </div>

        <!-- Formulário de Envio -->
        <div class="chatbot-footer p-2 px-3 bg-white border-top">
            <form id="chatbotForm" class="d-flex align-items-center gap-2 mb-0">
                @csrf
                <input type="text" 
                       id="chatbotInput" 
                       class="form-control form-control-sm rounded-pill border-1 px-3" 
                       placeholder="Pergunte sobre um jornal, gibi, horário..." 
                       maxlength="300"
                       autocomplete="off" 
                       required>
                <button type="submit" 
                        id="chatbotSubmitBtn" 
                        class="btn btn-sm btn-brand-accent rounded-circle d-flex align-items-center justify-content-center p-0 flex-shrink-0" 
                        style="width: 36px; height: 36px;" 
                        aria-label="Enviar mensagem">
                    <i class="bi bi-send-fill" style="font-size: 0.85rem; margin-left: 2px;"></i>
                </button>
            </form>
            <div class="d-flex justify-content-between align-items-center mt-1 px-1">
                <span class="text-muted" style="font-size: 0.68rem;">Banca Santa Rita • Expositor Digital</span>
                <a href="{{ $chatbotConfig->getWhatsappUrl() }}" target="_blank" class="text-success text-decoration-none fw-semibold" style="font-size: 0.68rem;">
                    <i class="bi bi-whatsapp me-1"></i> WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('chatbotToggleBtn');
    const toggleIcon = document.getElementById('chatbotToggleIcon');
    const chatWindow = document.getElementById('chatbotWindow');
    const closeBtn = document.getElementById('chatbotCloseBtn');
    const resetBtn = document.getElementById('chatbotResetBtn');
    const form = document.getElementById('chatbotForm');
    const input = document.getElementById('chatbotInput');
    const messagesContainer = document.getElementById('chatbotMessages');
    const typingIndicator = document.getElementById('chatbotTyping');

    if (!toggleBtn || !chatWindow || !form) return;

    let isChatOpen = false;

    // Abrir/Fechar Janela
    function toggleChat(forceOpen = null) {
        isChatOpen = forceOpen !== null ? forceOpen : !isChatOpen;
        if (isChatOpen) {
            chatWindow.classList.remove('d-none');
            toggleBtn.setAttribute('aria-expanded', 'true');
            if (toggleIcon) toggleIcon.className = 'bi bi-chevron-down ms-1 small';
            setTimeout(() => input.focus(), 150);
        } else {
            chatWindow.classList.add('d-none');
            toggleBtn.setAttribute('aria-expanded', 'false');
            if (toggleIcon) toggleIcon.className = 'bi bi-chevron-up ms-1 small';
        }
    }

    toggleBtn.addEventListener('click', () => toggleChat());
    closeBtn.addEventListener('click', () => toggleChat(false));

    // Reiniciar Conversa
    resetBtn.addEventListener('click', () => {
        messagesContainer.innerHTML = `
            <div class="chatbot-msg assistant d-flex align-items-start gap-2">
                <div class="chatbot-avatar-sm rounded-circle bg-success bg-opacity-10 text-success p-1 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px;">
                    <i class="bi bi-robot small"></i>
                </div>
                <div class="chatbot-bubble p-3 rounded-4 shadow-xs" style="background-color: #f1f5f2; color: #1e2920; border-bottom-left-radius: 4px;">
                    <p class="mb-1">Conversa reiniciada! Como posso ajudar você agora? 📚</p>
                </div>
            </div>
            <div id="chatbotSuggestions" class="d-flex flex-wrap gap-1 ps-4 ms-2">
                <button type="button" class="btn btn-sm btn-outline-success rounded-pill py-1 px-2 chatbot-chip" style="font-size: 0.75rem;" data-question="Qual o horário de funcionamento?">🕒 Horário</button>
                <button type="button" class="btn btn-sm btn-outline-success rounded-pill py-1 px-2 chatbot-chip" style="font-size: 0.75rem;" data-question="Onde fica a banca?">📍 Localização</button>
                <button type="button" class="btn btn-sm btn-outline-success rounded-pill py-1 px-2 chatbot-chip" style="font-size: 0.75rem;" data-question="Quais os gibis e mangás em estoque?">🦸 Mangás & HQs</button>
                <button type="button" class="btn btn-sm btn-outline-success rounded-pill py-1 px-2 chatbot-chip" style="font-size: 0.75rem;" data-question="Falar no WhatsApp com atendente">💬 WhatsApp</button>
            </div>
        `;
        attachChipListeners();
    });

    // Enviar Mensagem do Usuário
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const userText = input.value.trim();
        if (!userText) return;

        appendUserMessage(userText);
        input.value = '';
        sendMessageToServer(userText);
    });

    // Evento dos Chips de Sugestões
    function attachChipListeners() {
        document.querySelectorAll('.chatbot-chip').forEach(btn => {
            btn.onclick = function() {
                const question = this.getAttribute('data-question');
                if (question) {
                    appendUserMessage(question);
                    sendMessageToServer(question);
                }
            };
        });
    }
    attachChipListeners();

    function appendUserMessage(text) {
        const userDiv = document.createElement('div');
        userDiv.className = 'chatbot-msg user d-flex align-items-start justify-content-end gap-2';
        userDiv.innerHTML = `
            <div class="chatbot-bubble p-3 rounded-4 shadow-xs text-white" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-bottom-right-radius: 4px; max-width: 82%;">
                ${escapeHtml(text)}
            </div>
            <div class="rounded-circle bg-dark text-white p-1 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px; font-size: 0.75rem;">
                <i class="bi bi-person-fill"></i>
            </div>
        `;
        messagesContainer.appendChild(userDiv);
        scrollToBottom();

        // Oculta sugestões antigas após interação
        const sug = document.getElementById('chatbotSuggestions');
        if (sug) sug.remove();
    }

    function appendAssistantMessage(data) {
        const assistantDiv = document.createElement('div');
        assistantDiv.className = 'chatbot-msg assistant d-flex align-items-start gap-2';

        let formattedReply = escapeHtml(data.reply || '')
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\n/g, '<br>');

        let itemsHtml = '';
        if (data.items && data.items.length > 0) {
            itemsHtml += '<div class="chatbot-products-deck mt-2 d-flex flex-column gap-2">';
            data.items.forEach(item => {
                itemsHtml += `
                    <div class="card p-2 rounded-3 border bg-white shadow-xs">
                        <div class="d-flex align-items-center gap-2">
                            <img src="${escapeHtml(item.imagem)}" alt="${escapeHtml(item.nome)}" style="width: 50px; height: 50px; object-fit: cover;" class="rounded-2 border flex-shrink-0">
                            <div class="flex-grow-1 overflow-hidden">
                                <span class="badge badge-category py-0 px-1" style="font-size: 0.68rem;">${escapeHtml(item.categoria)}</span>
                                <div class="fw-bold text-truncate text-dark" style="font-size: 0.82rem;">${escapeHtml(item.nome)}</div>
                                <div class="text-success fw-bold" style="font-size: 0.78rem;">${escapeHtml(item.preco)}</div>
                            </div>
                        </div>
                        <div class="d-flex gap-1 mt-2">
                            <a href="${escapeHtml(item.url)}" class="btn btn-sm btn-outline-secondary py-0 px-2 flex-grow-1 text-center" style="font-size: 0.72rem;">
                                Ver Detalhes
                            </a>
                            <a href="${escapeHtml(item.whatsapp_url)}" target="_blank" class="btn btn-sm btn-brand-accent py-0 px-2 flex-grow-1 text-center" style="font-size: 0.72rem;">
                                <i class="bi bi-whatsapp me-1"></i> Reservar
                            </a>
                        </div>
                    </div>
                `;
            });
            itemsHtml += '</div>';
        }

        let whatsappBtnHtml = '';
        if (data.whatsapp_url) {
            whatsappBtnHtml = `
                <div class="mt-2">
                    <a href="${escapeHtml(data.whatsapp_url)}" target="_blank" class="btn btn-sm btn-brand-accent w-100 py-1 d-flex align-items-center justify-content-center gap-1 shadow-xs" style="font-size: 0.75rem;">
                        <i class="bi bi-whatsapp"></i> Falar com Atendente no WhatsApp
                    </a>
                </div>
            `;
        }

        let suggestionsHtml = '';
        if (data.suggestions && data.suggestions.length > 0) {
            suggestionsHtml += '<div class="d-flex flex-wrap gap-1 mt-2">';
            data.suggestions.forEach(s => {
                suggestionsHtml += `
                    <button type="button" class="btn btn-sm btn-outline-success rounded-pill py-0 px-2 chatbot-chip" style="font-size: 0.72rem;" data-question="${escapeHtml(s)}">
                        ${escapeHtml(s)}
                    </button>
                `;
            });
            suggestionsHtml += '</div>';
        }

        assistantDiv.innerHTML = `
            <div class="chatbot-avatar-sm rounded-circle bg-success bg-opacity-10 text-success p-1 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px;">
                <i class="bi bi-robot small"></i>
            </div>
            <div class="chatbot-bubble p-3 rounded-4 shadow-xs" style="background-color: #f1f5f2; color: #1e2920; border-bottom-left-radius: 4px; max-width: 88%;">
                <div>${formattedReply}</div>
                ${itemsHtml}
                ${whatsappBtnHtml}
                ${suggestionsHtml}
            </div>
        `;

        messagesContainer.appendChild(assistantDiv);
        scrollToBottom();
        attachChipListeners();
    }

    function sendMessageToServer(message) {
        typingIndicator.classList.remove('d-none');
        typingIndicator.classList.add('d-flex');
        scrollToBottom();

        const token = document.querySelector('input[name="_token"]')?.value || '';

        fetch('{{ route("chatbot.message") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 429) {
                    throw new Error('Você enviou muitas mensagens rapidamente. Por favor, aguarde alguns segundos.');
                }
                throw new Error('Não foi possível processar a resposta agora.');
            }
            return response.json();
        })
        .then(data => {
            typingIndicator.classList.add('d-none');
            typingIndicator.classList.remove('d-flex');
            appendAssistantMessage(data);
        })
        .catch(err => {
            typingIndicator.classList.add('d-none');
            typingIndicator.classList.remove('d-flex');
            appendAssistantMessage({
                reply: `Desculpe, ocorreu uma instabilidade momentânea: ${err.message || 'Tente novamente em breve.'}`,
                whatsapp_url: '{{ $chatbotConfig->getWhatsappUrl() }}',
                suggestions: ['🕒 Horário de funcionamento', '📍 Onde fica a banca?']
            });
        });
    }

    function scrollToBottom() {
        setTimeout(() => {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }, 50);
    }

    function escapeHtml(string) {
        const div = document.createElement('div');
        div.innerText = string;
        return div.innerHTML;
    }
});
</script>
