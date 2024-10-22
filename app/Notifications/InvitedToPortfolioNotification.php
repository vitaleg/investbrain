<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Portfolio;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class InvitedToPortfolioNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Portfolio $portfolio,
        public User $sender,
    ) { }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {

        return (new MailMessage)
                    ->replyTo($this->sender->email, $this->sender->name)
                    ->greeting('Hey there! 👋')
                    ->subject("{$this->sender->name} invited you to {$this->portfolio->title} on Investbrain!")
                    ->line("{$this->sender->name} has invited you to **{$this->portfolio->title}** on Investbrain, Smart open-source investment tracker that consolidates and monitors market performance across your different brokerages.")
                    ->line('Once you\'re in, you\'ll be able to see holdings, dividends, market performance and more!')
                    ->action("Get Started", route('portfolio.show', ['portfolio' => $this->portfolio->id]))
                    ->line("If you have any questions, you can reply to this email.")
                    ->salutation("See you there,\n". e($this->sender->name));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
