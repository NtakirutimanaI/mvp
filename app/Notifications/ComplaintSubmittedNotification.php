<?php

// app/Notifications/ComplaintSubmittedNotification.php

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Complaint;

class ComplaintSubmittedNotification extends Notification
{
    protected $complaint;

    public function __construct(Complaint $complaint)
    {
        $this->complaint = $complaint;
    }

    public function via($notifiable)
    {
        return ['mail']; // You can add 'database', 'sms', etc.
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Complaint Received')
            ->line('A new complaint has been submitted to your institution.')
            ->line('Subject: ' . $this->complaint->subject)
            ->line('Category: ' . $this->complaint->category)
            ->line('Description: ' . $this->complaint->description)
            ->action('View Complaint', url('/admin/complaints/' . $this->complaint->id));
    }
}
