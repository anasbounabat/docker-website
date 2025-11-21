<div class="w-full min-h-screen bg-black text-white pt-16 pb-4 px-4 flex justify-center">
    <div class="w-full max-w-[1200px] flex flex-col bg-black/60 border border-white/10 rounded-[32px] shadow-2xl backdrop-blur-xl overflow-hidden transition duration-300" style="max-height:calc(100vh - 96px);">
        <div class="border-b border-white/10 bg-black/80 px-8 py-6">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div class="space-y-1">
                    <p class="text-xs uppercase tracking-[0.4em] text-emerald-300/70">Canal public</p>
                    <h1 class="text-3xl font-extrabold tracking-tight text-white drop-shadow-sm">Conversation en direct</h1>
                </div>
                <div class="text-right">
                    <p class="text-[10px] text-slate-300 uppercase tracking-[0.4em]">Statut</p>
                    <span class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-300">
                        <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        En ligne
                    </span>
                </div>
            </div>
        </div>

        <div id="chat-messages" class="flex-1 overflow-y-auto p-6 space-y-5 bg-black/80">
            @forelse($messages as $message)
                @php
                    $isOwn = $message['is_own'] ?? false;
                @endphp

                <div class="flex {{ $isOwn ? 'justify-end' : 'justify-start' }}">
                    <div class="relative w-full lg:w-3/4 px-4 py-3 rounded-2xl shadow-2xl {{ $isOwn ? 'bg-gradient-to-r from-emerald-600 to-emerald-500 text-white' : 'bg-emerald-500/90 text-white border border-emerald-400/70' }}">
                        @if(!$isOwn)
                            <div class="text-[10px] font-semibold text-emerald-200/80 mb-2 uppercase tracking-[0.4em]">
                                {{ trim(($message['first_name'] ?? '') . ' ' . ($message['last_name'] ?? $message['name'] ?? '')) ?: 'Utilisateur' }}
                            </div>
                        @endif
                        <div class="text-base leading-relaxed break-words">{{ $message['content'] }}</div>
                        <div class="text-[10px] mt-3 uppercase tracking-[0.2em] text-emerald-100/70">
                            {{ \Carbon\Carbon::parse($message['created_at'])->format('H:i') }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-emerald-200/80 py-8">
                    <p>Aucun message. Soyez le premier à écrire !</p>
                </div>
            @endforelse
        </div>

        <div class="bg-black/80 border-t border-white/10 p-6 backdrop-blur-md">
            <form wire:submit.prevent="sendMessage" class="flex flex-col gap-3 lg:flex-row">
                <input 
                    type="text" 
                    wire:model="content" 
                    placeholder="Tapez votre message..." 
                    class="flex-1 rounded-2xl border border-emerald-400/50 bg-black/60 px-4 py-3 placeholder:text-white/60 focus:outline-none focus:border-emerald-300 focus:bg-black/70 focus:ring-2 focus:ring-emerald-400 transition"
                    autocomplete="off"
                >
                <button 
                    type="submit" 
                    class="flex items-center justify-center gap-2 px-6 py-3 bg-emerald-500 text-white font-semibold rounded-2xl shadow-lg hover:bg-emerald-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-400 disabled:opacity-50 transition"
                    wire:loading.attr="disabled"
                >
                    <span>Envoyer</span>
                </button>
            </form>
        </div>
    </div>
</div>

@script
<script>
    window.scrollToBottom = function() {
        const chatContainer = document.getElementById('chat-messages');
        if(chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    };

    const scrollDuty = () => window.scrollToBottom();

    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(scrollDuty, 100);
    });
    document.addEventListener('livewire:load', () => {
        setTimeout(scrollDuty, 100);
    });
    
    Livewire.hook('morph.updated', () => {
        setTimeout(scrollDuty, 50);
    });

    if (window.Echo) {
        window.Echo.channel('chat')
            .listen('.message.sent', (e) => {
                $wire.loadMessages();
                setTimeout(scrollDuty, 100);
            });
    }
</script>
<style>
    #chat-messages::-webkit-scrollbar {
        width: 0;
        height: 0;
    }

    #chat-messages {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endscript
