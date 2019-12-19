<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Telegram;
use Telegram\Bot\Keyboard\Keyboard;

use App\Services\TelegramCommandService;


class TelegramController extends Controller
{
    public function webhook(Request $request) {
        $update = Telegram::commandsHandler(true);

        $message = $update->getMessage();
        $text = $message->getText();
        $mid = $message->getId();
        $cid = $message->getFrom()->getId();

        // Checking if incoming update is a Callback
        $callbackQuery = $update->getCallbackQuery();
        if ($callbackQuery) {
            $cbid = $callbackQuery->getFrom()->getId();
            $cbdata = $callbackQuery->getData();
            $btext = $callbackQuery->getMessage()->getText();

            Telegram::answerCallbackQuery([
                'callback_query_id' => $update->getCallbackQuery()->getFrom()->getId(),
                'cache_time' => 1
            ]);

            if ($cbdata == '/client') {
                $service = new TelegramCommandService();
                $service->handleClientCommand($update);
            }
        } else {
            $keyboard = Keyboard::make()
                ->inline()
                ->row(
                    Keyboard::inlineButton(['text' => 'Nuova ricerca', 'callback_data' => '/client'])
                );
            Telegram::sendMessage([
                'chat_id' => $cid,
                'text' => $text,
                'reply_markup' => $keyboard,
                'parse_mode' => 'html'
            ]);
        }

        return;
    }
}
