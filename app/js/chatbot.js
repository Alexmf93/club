 // Variables del chatbot
        const chatbotMessages = document.getElementById('chatbotMessages');
        const chatbotUserInput = document.getElementById('chatbotUserInput');
        const chatbotContainer = document.getElementById('chatbotContainer');
        const chatbotToggle = document.getElementById('chatbotToggle');
        const chatbotQuickReplies = document.getElementById('chatbotQuickReplies');

        // Base de conocimientos del club
        const clubInfo = {
            contacto: {
                telefono: '+34 958 123 456',
                email: 'info@clubsocios.es',
                direccion: 'Calle Principal 123, Churriana de la Vega, Granada',
                whatsapp: '+34 600 123 456'
            },
            horarios: {
                general: 'Lunes a Viernes: 7:00 - 22:00, Sábados y Domingos: 8:00 - 21:00',
                deportes: 'Lunes a Domingo: 8:00 - 22:00',
                relax: 'Lunes a Domingo: 10:00 - 21:00'
            },
            servicios: {
                baloncesto: {
                    nombre: 'Reserva de Pista de Baloncesto',
                    descripcion: 'Contamos con 4 pistas de Baloncesto profesionales',
                    precio: '56.00€ / 90 min',
                    reserva: 'Puedes reservar llamando al 958 123 456 o mediante nuestra app'
                },
                spinning: {
                    nombre: 'Reserva de Clase de Spinning',
                    descripcion: 'Clase de spinning profesional',
                    precio: '12.50€ / 45 min',
                    reserva: 'Puedes reservar llamando al 958 123 456 o mediante nuestra app'
                },
                futbol: {
                    nombre: 'Campo de fútbol',
                    descripcion: 'Campo de futbol 7 y 11',
                    precio: '88.00€ / partido',
                    acceso: 'Puedes reservar llamando al 958 123 456 o mediante nuestra app'
                },
                rugby: {
                    nombre: 'Campo de Rugby',
                    descripcion: 'Campo de rugby profesional',
                    precio: '60.00€ / 40 min',
                    entrenador: 'Entrenador personal disponible con cita previa'
                },
                sauna: {
                    nombre: 'Servicio de Sauna',
                    descripcion: 'Sauna finlandesa y baño turco',
                    precio: '4.00€ / 30 min',
                    acceso: 'Acceso libre durante el horario de apertura'
                },
                solarium: {
                    nombre: 'Servicio de Solarium',
                    descripcion: 'Solarium profesional',
                    precio: '7.00€ / 15 min',
                    acceso: 'Puedes reservar llamando al 958 123 456 o mediante nuestra app'
                },
            }
        };

        // Opciones de respuesta rápida
        const quickReplyOptions = [
            { text: '📞 Contacto', value: 'contacto' },
            { text: '🕐 Horarios', value: 'horarios' },
            { text: '⛹️ Baloncesto', value: 'baloncesto' },
            { text: '🧖 Sauna', value: 'sauna' },
            { text: '💪 Spinning', value: 'spinning' },
            { text: '⚽ Futbol', value: 'futbol' },
            { text: '🏀 Rugby', value: 'rugby' },
            { text: '😊 Solarium', value: 'solarium' }
        ];

        // Inicializar chatbot
        function initChatbot() {
            addChatbotBotMessage('¡Hola! Bienvenido al Club de Socios. Soy tu asistente virtual. ¿En qué puedo ayudarte hoy?');
            showChatbotQuickReplies();
        }

        // Añadir mensaje del bot
        function addChatbotBotMessage(text) {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'chatbot-message bot';
            messageDiv.innerHTML = `<div class="chatbot-message-content">${text}</div>`;
            chatbotMessages.appendChild(messageDiv);
            scrollChatbotToBottom();
        }

        // Añadir mensaje del usuario
        function addChatbotUserMessage(text) {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'chatbot-message user';
            messageDiv.innerHTML = `<div class="chatbot-message-content">${text}</div>`;
            chatbotMessages.appendChild(messageDiv);
            scrollChatbotToBottom();
        }

        // Procesar mensaje del usuario
        function processChatbotMessage(message) {
            const lowerMessage = message.toLowerCase();
            
            // Contacto
            if (lowerMessage.includes('contacto') || lowerMessage.includes('teléfono') || 
                lowerMessage.includes('telefono') || lowerMessage.includes('email') || 
                lowerMessage.includes('dirección') || lowerMessage.includes('direccion') ||
                lowerMessage.includes('whatsapp')) {
                return `📞 <strong>Información de Contacto:</strong><br><br>
                        📱 Teléfono: ${clubInfo.contacto.telefono}<br>
                        📧 Email: ${clubInfo.contacto.email}<br>
                        📍 Dirección: ${clubInfo.contacto.direccion}<br>
                        💬 WhatsApp: ${clubInfo.contacto.whatsapp}`;
            }
            
            // Horarios generales
            if (lowerMessage.includes('horario') && !lowerMessage.includes('sauna') && !lowerMessage.includes('solarium')) {
                return `🕐 <strong>Horarios del Club:</strong><br><br>
                        🏢 General: ${clubInfo.horarios.general}<br><br>
                        Servicios específicos:<br>
                        ⚽ Deportes (Fútbol, Rugby, Basket): ${clubInfo.horarios.deportes}<br>
                        🧖 Relax (Sauna, Solarium): ${clubInfo.horarios.relax}`;
            }
            
            // Baloncesto
            if (lowerMessage.includes('baloncesto') || lowerMessage.includes('basket')) {
                return `🏀 <strong>${clubInfo.servicios.baloncesto.nombre}</strong><br><br>
                        ${clubInfo.servicios.baloncesto.descripcion}<br><br>
                        💰 Precio: ${clubInfo.servicios.baloncesto.precio}<br>
                        📅 ${clubInfo.servicios.baloncesto.reserva}`;
            }

            // Spinning
            if (lowerMessage.includes('spinning') || lowerMessage.includes('bici')) {
                return `🚴 <strong>${clubInfo.servicios.spinning.nombre}</strong><br><br>
                        ${clubInfo.servicios.spinning.descripcion}<br><br>
                        💰 Precio: ${clubInfo.servicios.spinning.precio}<br>
                        📅 ${clubInfo.servicios.spinning.reserva}`;
            }

            // Futbol
            if (lowerMessage.includes('futbol') || lowerMessage.includes('fútbol')) {
                return `⚽ <strong>${clubInfo.servicios.futbol.nombre}</strong><br><br>
                        ${clubInfo.servicios.futbol.descripcion}<br><br>
                        💰 Precio: ${clubInfo.servicios.futbol.precio}<br>
                        📅 ${clubInfo.servicios.futbol.acceso}`;
            }

            // Rugby
            if (lowerMessage.includes('rugby')) {
                return `🏉 <strong>${clubInfo.servicios.rugby.nombre}</strong><br><br>
                        ${clubInfo.servicios.rugby.descripcion}<br><br>
                        💰 Precio: ${clubInfo.servicios.rugby.precio}<br>
                        ℹ️ ${clubInfo.servicios.rugby.entrenador}`;
            }
            
            // Sauna
            if (lowerMessage.includes('sauna') || lowerMessage.includes('baño') || 
                lowerMessage.includes('turco')) {
                return `🧖 <strong>${clubInfo.servicios.sauna.nombre}</strong><br><br>
                        ${clubInfo.servicios.sauna.descripcion}<br><br>
                        💰 ${clubInfo.servicios.sauna.precio}<br>
                        🚪 ${clubInfo.servicios.sauna.acceso}<br>
                        🕐 Horario: ${clubInfo.horarios.relax}`;
            }
            
            // Solarium
            if (lowerMessage.includes('solarium') || lowerMessage.includes('bronceado')) {
                return `☀️ <strong>${clubInfo.servicios.solarium.nombre}</strong><br><br>
                        ${clubInfo.servicios.solarium.descripcion}<br><br>
                        💰 ${clubInfo.servicios.solarium.precio}<br>
                        🕐 Horario: ${clubInfo.horarios.relax}`;
            }
            
            // Servicios generales
            if (lowerMessage.includes('servicio') || lowerMessage.includes('qué ofrece') || 
                lowerMessage.includes('que ofrece') || lowerMessage.includes('instalaciones')) {
                return `✨ <strong>Nuestros Servicios:</strong><br><br>
                        ⚽ Deportes: Fútbol, Baloncesto, Rugby<br>
                        🚴 Clases: Spinning<br>
                        🧖 Relax: Sauna, Solarium<br><br>
                        ¿Sobre qué servicio te gustaría saber más?`;
            }
            
            // Precio/Cuota
            if (lowerMessage.includes('precio') || lowerMessage.includes('cuota') || 
                lowerMessage.includes('coste') || lowerMessage.includes('cuesta')) {
                return `💰 <strong>Información de Precios:</strong><br><br>
                        ⚽ Fútbol: ${clubInfo.servicios.futbol.precio}<br>
                        🏀 Baloncesto: ${clubInfo.servicios.baloncesto.precio}<br>
                        🚴 Spinning: ${clubInfo.servicios.spinning.precio}<br>
                        🧖 Sauna: ${clubInfo.servicios.sauna.precio}<br>
                        ☀️ Solarium: ${clubInfo.servicios.solarium.precio}<br><br>
                        Para información sobre cuotas de socio, contacta con nosotros.`;
            }
            
            // Saludo
            if (lowerMessage.includes('hola') || lowerMessage.includes('buenos') || 
                lowerMessage.includes('buenas')) {
                return '¡Hola! 👋 Estoy aquí para ayudarte con información sobre el club. ¿Qué necesitas saber?';
            }
            
            // Gracias
            if (lowerMessage.includes('gracias') || lowerMessage.includes('perfecto') || 
                lowerMessage.includes('vale') || lowerMessage.includes('ok')) {
                return '¡De nada! 😊 Si necesitas algo más, aquí estoy para ayudarte.';
            }
            
            // Respuesta por defecto
            return `No estoy seguro de cómo ayudarte con eso. Puedo informarte sobre:<br><br>
                    📞 Información de contacto<br>
                    🕐 Horarios del club<br>
                    ⚽ Deportes (Fútbol, Basket, Rugby)<br>
                    🚴 Spinning<br>
                    🧖 Sauna y Solarium<br><br>
                    ¿Sobre qué te gustaría saber?`;
        }

        // Mostrar respuestas rápidas
        function showChatbotQuickReplies() {
            chatbotQuickReplies.innerHTML = '';
            quickReplyOptions.forEach(option => {
                const btn = document.createElement('button');
                btn.className = 'quick-reply-btn';
                btn.textContent = option.text;
                btn.onclick = () => handleChatbotQuickReply(option.value, option.text);
                chatbotQuickReplies.appendChild(btn);
            });
        }

        // Manejar respuesta rápida
        function handleChatbotQuickReply(value, text) {
            addChatbotUserMessage(text);
            setTimeout(() => {
                const response = processChatbotMessage(value);
                addChatbotBotMessage(response);
            }, 800);
        }

        // Enviar mensaje
        function chatbotSendMessage() {
            const message = chatbotUserInput.value.trim();
            if (message === '') return;

            addChatbotUserMessage(message);
            chatbotUserInput.value = '';

            setTimeout(() => {
                const response = processChatbotMessage(message);
                addChatbotBotMessage(response);
            }, 1000);
        }

        // Scroll al final
        function scrollChatbotToBottom() {
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
        }

        // Event listener para Enter
        chatbotUserInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                chatbotSendMessage();
            }
        });

        // Toggle chatbot
        chatbotToggle.addEventListener('click', () => {
            if (chatbotContainer.style.display === 'none' || chatbotContainer.style.display === '') {
                chatbotContainer.style.display = 'flex';
                if (chatbotMessages.children.length === 0) {
                    initChatbot();
                }
            } else {
                chatbotContainer.style.display = 'none';
            }
        });

        // Inicializar chatbot si está visible
        if (chatbotContainer.style.display === 'flex') {
            initChatbot();
        }