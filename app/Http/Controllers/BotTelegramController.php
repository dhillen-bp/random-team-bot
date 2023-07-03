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
                'text'      => 'Hello ' . $username
            ]);
        } elseif (strtolower($command) === '/randomteam') {
            // Send a message to request team members from the user
            return Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => "Please enter team members, separated by commas (,).\n(For example: /randomteam member1, member2, member3)\n Make sure after /randomteam put a space,\n - correct example: /randomteam member1, member2 ✅\n - wrong example: /randomteammember1, member2 ❌"
            ]);
        } elseif (strpos($command, '/randomteam') === 0) {
            // Process commands to generate random teams
            $commandParts = explode(' ', $command);
            $numTeams = 2; // Default number of teams

            if (count($commandParts) > 1 && is_numeric($commandParts[1])) {
                $numTeams = max(2, (int)$commandParts[1]); // Uses number of teams entered by the user
            }

            // Check if the number of teams is less than 2
            if ($numTeams < 2) {
                return Telegram::sendMessage([
                    'chat_id' => $chat_id,
                    'text' => "The minimum number of teams is 2."
                ]);
            }

            $teamMembersInput = preg_replace('/[^a-zA-Z0-9\s,]/', '', $command);
            $teamMembersInput = str_replace('/randomteam', '', $command);
            $teamMembersInput = trim($teamMembersInput);
            $teamMembersInput = explode(',', $teamMembersInput);
            $teamMembersInput = array_map('trim', $teamMembersInput);

            if (count($teamMembersInput) < $numTeams) {
                return Telegram::sendMessage([
                    'chat_id' => $chat_id,
                    'text' => "Not enough team members provided."
                ]);
            }

            shuffle($teamMembersInput);

            $teamSize = ceil(count($teamMembersInput) / $numTeams);

            $teams = array_chunk($teamMembersInput, $teamSize);

            $responseText = "Random Teams:\n";
            foreach ($teams as $index => $team) {
                $responseText .= "Team " . ($index + 1) . ":\n";
                foreach ($team as $member) {
                    // Check if the member starts with a number
                    if (is_numeric(substr($member, 0, 1))) {
                        $responseText .= "- " . substr($member, 2) . "\n";
                    } else {
                        $responseText .= "- " . $member . "\n";
                    }
                }
            }

            return Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => $responseText,
            ]);
        } elseif (strtolower($command) === '/help') {
            // List of available commands
            $availableCommands = [
                '/halo - Reply with username',
                '/randomteam - Generate random teams',
                '/help - Show available commands',
                // Add more commands as needed
            ];

            $responseText = "Available commands:\n";
            foreach ($availableCommands as $command) {
                $responseText .= $command . "\n";
            }

            return Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => $responseText,
            ]);
        }
    }
}
