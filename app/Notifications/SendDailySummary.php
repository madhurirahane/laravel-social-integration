<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
//use App\Mail\DailySummarySent;
use Log;

class SendDailySummary extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($heading, $user)
    {
        //$this->when = $when;
        $this->heading = $heading;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    /**
     * Get the slack notification.
     */
    public function toSlack($notifiable)
    {
        //dd("notifiable");
        return (new SlackMessage())
            ->success()
            ->to('#daily_summary')
            ->content($this->heading)
            ->attachment(function ($attachment) {
                $attachment->title('Summary', url('/'))
                ->fields(
                    [
                        'User ' => $this->user,
                    ]
                );
            });
    }
}
