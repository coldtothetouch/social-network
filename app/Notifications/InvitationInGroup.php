<?php

namespace App\Notifications;

use App\Models\Group;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvitationInGroup extends Notification
{
    use Queueable;

    public function __construct(
        public Group  $group,
        public string $token,
        public string $hours,
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
            ->line("You have been invited in group \"{$this->group->name}\".")
            ->action('Join', url(route('groups.join', $this->token)))
            ->line("The link will be valid for next $this->hours hours.");
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
