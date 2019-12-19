<?php

namespace App\Services;

use Telegram;
use Log;

class TelegramCommandService
{
    public function handleClientCommand($update = null) {
        // Handling /client command
        Log::debug("It's working!");

        if (!empty($update)) {
            $chat_id = $update->getMessage()->getFrom()->getId();

            Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => 'Hello from /client command!'
            ]);
        }
    }
}
