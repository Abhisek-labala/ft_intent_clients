<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\Exception\FirebaseException;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        // Initialize Firebase with service account credentials
        $factory = (new Factory)->withServiceAccount(config('firebase.credentials'));

        // Initialize the Messaging service
        $this->messaging = $factory->createMessaging();
    }

    public function sendNotification($token, $title, $body)
    {
        $notification = Messaging\Notification::create($title, $body);
        
        $message = Messaging\CloudMessage::withTarget('token', $token)
            ->withNotification($notification);

        try {
            $this->messaging->send($message);
        } catch (FirebaseException $e) {
            // Handle any exceptions here
            return ['success' => false, 'error' => $e->getMessage()];
        }

        return ['success' => true];
    }
}
