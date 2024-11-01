<?php

namespace App\Notifications;

use App\Models\Group;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestApproved extends Notification
{
    use Queueable;

    public function __construct(
        public Group $group,
        public User  $user,
        public bool  $approved,
    )
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $action = $this->approved ? 'approved' : 'rejected';

        return (new MailMessage)
            ->subject("Request was $action")
            ->line("Your request to join group\"{$this->group->name}\" has been $action.")
            ->action('Open Group', url(route('groups.show', $this->group)))
            ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
