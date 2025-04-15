<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Comment;

class CommentNotification extends Notification
{
    use Queueable;

    public $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function via($notifiable)
    {
        return ['database']; // Simpan notifikasi di database
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "{$this->comment->user->name} commented on your post.",
            'link' => "/posts/{$this->comment->post_id}",
        ];
    }
}
