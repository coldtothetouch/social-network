<?php

namespace App\Notifications;

use App\Models\Group;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPost extends Notification
{
    use Queueable;

    public function __construct(
        public Post $post,
        public ?Group $group = null,
    )
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->lineIf(!!$this->group,"New post was added in {$this->group?->name}.")
                    ->lineIf(!$this->group,"New post was added by user {$this->post->user->name}.")
                    ->action('View Post', url(route('posts.show', $this->post)))
                    ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
