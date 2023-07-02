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

        if ($command === '/start') {
            $response = Telegram::sendMessage([
                'chat_id'   => $chat_id,
                'text'      => 'Type /help to show the commands'
            ]);
            // return response()->json(['response' => $response]);
        } elseif (strtolower($command === '/halo')) {
            return Telegram::sendMessage([
                'chat_id'   => $chat_id,
                'text'      => 'Hallo ' . $username
            ]);
        } elseif (strtolower($command) === '/randomteam') {
            // Mengirim pesan untuk meminta anggota tim dari pengguna
            return Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => "Please enter team members, separated by commas (,).\n(For example: /randomteam member1, member2, member3)\n Make sure after /randomteam put a space,\n - correct example: /randomteam member1, member2 ✅\n - wrong example: /randomteammember1, member2 ❌"
            ]);
        } elseif (strpos($command, '/randomteam') === 0) {
            // Memproses perintah untuk menghasilkan tim-tim acak
            $teamMembersInput = str_replace('/randomteam ', '', $command);
            $teamMembersInput = explode(',', $teamMembersInput);
            $teamMembersInput = array_map('trim', $teamMembersInput);

            shuffle($teamMembersInput);

            $numTeams = 2;

            $teams = array_chunk($teamMembersInput, count($teamMembersInput) / $numTeams);

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
}
