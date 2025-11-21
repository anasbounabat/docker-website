<?php

namespace App\Livewire;

use App\Events\MessageSent;
use App\Models\Message;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Chat extends Component
{
    public $messages = [];
    public $content = '';

    public function mount()
    {
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $this->messages = Message::with('user')
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'user_id' => $message->user_id,
                    'first_name' => $message->user->first_name ?? 'Utilisateur',
                    'is_own' => $message->user_id === auth()->id(),
                    'content' => $message->content,
                    'created_at' => $message->created_at->toDateTimeString(),
                ];
            })
            ->values()
            ->toArray();
    }

    public function sendMessage()
    {
        $this->validate([
            'content' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'user_id' => auth()->id(),
            'content' => $this->content,
        ]);

        $message->load('user');

        event(new MessageSent($message));

        $this->content = '';
        
        // Recharger les messages après envoi
        $this->loadMessages();
    }

    #[On('echo:chat,message.sent')]
    public function handleMessageSent($event)
    {
        $this->loadMessages();
    }

    public function render()
    {
        return view('livewire.chat');
    }
}

