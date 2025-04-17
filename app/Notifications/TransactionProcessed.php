<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TransactionProcessed extends Notification
{
    use Queueable;

    protected $transaction;

    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    public function via($notifiable)
    {
        return ['broadcast'];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => 'New Payment Received',
            'body' => 'Payment of $' . $this->transaction->amount_inr . ' received for Order ID: ' . $this->transaction->order_id,
            'transaction_id' => $this->transaction->id
        ]);
    }
}
