<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class OrderCreatedNotification extends Notification
{
    use Queueable;

    protected $order;
    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable)
    {
        return ['mail', 'database' , ];

    
        $channels = ['database'];
        if ($notifiable->notification_prefrences['order_created']['sms'] ?? false){
            $channels[] = 'vonage';
        }
        if ($notifiable->notification_prefrences['order_created']['mail'] ?? false){
            $channels[] = 'mail';
        }
        if ($notifiable->notification_prefrences['order_created']['broadcast'] ?? false){
            $channels[] = 'broadcast';
        }
        return $channels;
    }
    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $addr = $this->order->billingAddress;
        return (new MailMessage)
            ->subject("New Order Created #{$this->order->number}")
            ->greeting("Hello {$notifiable->name},")
            ->line("New order #{$this->order->number} has been created by {$addr->name} from {$addr->country_name}.")
            ->action('View Order', url('/dashboard'))
            ->line('Thank you for using our application!');
    }

    public function toDatabase($notifiable)
    {
        $addr = $this->order->billingAddress;
        return [
            'body' => "New order(#{$this->order->number}) has been created by {$addr->name} from {$addr->country_name}.",
            'icon' => 'fas fa-file',
            'url' =>  url('/dashboard'),
            'order_id' => $this->order->id,

        ];
           
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
