<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    public function sendMessage($target, $message)
    {
        $token = env('FONNTE_TOKEN');
        $sender = env('FONNTE_SENDER');

        $response = Http::withHeaders([
            'Authorization' => $token
        ])->post('https://api.fonnte.com/send', [
            'target' => $target,
            'message' => $message,
            'device' => $sender
        ]);

        return $response->json();
    }
}
