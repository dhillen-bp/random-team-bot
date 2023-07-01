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
        $updates = Telegram::getWebhookUpdates();
        $chat_id = $updates->getChat()->getId();
        $username = $updates->getChat()->getFirstName();
        $command = $updates->getMessage()->getText();

        if ($command === '/') {
            return;
        } elseif (strtolower($command === '/halo')) {
            return Telegram::sendMessage([
                'chat_id'   => $chat_id,
                'text'      => 'Halo ' . $username
            ]);
        } elseif (strtolower($command) === '/randomteam') {
            $teamMembers = [
                'Anggota 1',
                'Anggota 2',
                'Anggota 3',
                'Anggota 4',
                'Anggota 5',
                'Anggota 6',
                // Tambahkan anggota tim lainnya sesuai kebutuhan
            ];

            shuffle($teamMembers);

            $numTeams = 2;

            $teams = array_chunk($teamMembers, count($teamMembers) / $numTeams);

            $responseText = "Tim-tim acak:\n";
            foreach ($teams as $index => $team) {
                $responseText .= "Tim " . ($index + 1) . ":\n";
                foreach ($team as $member) {
                    $responseText .= "- " . $member . "\n";
                }
            }

            return Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => $responseText,
            ]);
        } elseif (strtolower($command) === '/help') {
            // Daftar perintah yang tersedia
            $availableCommands = [
                '/halo - Reply with username',
                '/randomteam - Generate random teams',
                '/help - Show available commands',
                // Tambahkan perintah lainnya sesuai kebutuhan
            ];

            // Membentuk teks respons dengan daftar perintah yang tersedia
            $responseText = "Available commands:\n";
            foreach ($availableCommands as $command) {
                $responseText .= $command . "\n";
            }

            // Mengirim pesan balasan dengan daftar perintah yang tersedia
            return Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => $responseText,
            ]);
        }
    }

    public function randomTeamHandler()
    {
        // $updates = Telegram::commandsHandler(true);
        // $chatId = $updates->getChat()->getId();
        // $message = $updates->getMessage()->getText();

        // if (strtolower($message) === '/randomteam') {
        // }
    }
}
