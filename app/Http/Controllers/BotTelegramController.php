<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class BotTelegramController extends Controller
{
    public function setWebhook()
    {
        $response = Telegram::setWebhook(['url' => env('TELEGRAM_WEBHOOK_URL')]);
        dd($response);
    }

    public function commandHandlerWebhook()
    {
        $updates = Telegram::commandsHandler(true);
        $chat_id = $updates->getChat()->getId();
        $username = $updates->getChat()->getFirstName();

        if (strtolower($updates->getMessage()->getText() == 'halo')) return Telegram::sendMessage([
            'chat_id'   => $chat_id,
            'text'      => 'Halo ' . $username
        ]);
    }
}
