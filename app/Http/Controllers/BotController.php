<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use DefStudio\Telegraph\Models\TelegraphChat;
use DefStudio\Telegraph\Models\TelegraphBot;


class BotController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->all();

        if (!isset($data['message']['chat']['id'])) {
            Log::error('Invalid data', $data);
            return response()->json(['error' => 'Invalid data'], 400);
        }

        $chat_id = $data['message']['chat']['id'];
        $text = $data['message']['text'];
        $user = User::where('telegram_id', $chat_id)->first();

        if ($text === '/start' && !$user) {
            $this->sendMessage($chat_id, 'Пожалуйста, отправьте свой номер телефона для верификации.');
            return;
        }

        if (!$user) {
            if ($this->isValidPhone($text)) {
                $user = User::create([
                    'nickname' => $data['message']['from']['username'] ?? null,
                    'phone' => $text,
                    'status' => 'active',
                    'telegram_id' => $chat_id,
                ]);
                $this->sendMessage($chat_id, 'Спасибо! Ваш номер телефона сохранен.');
            } else {
                $this->sendMessage($chat_id, 'Неправильный формат номера телефона. Пожалуйста, отправьте свой номер телефона еще раз.');
            }
            return;
        }

        if ($user->status == 'blocked') {
            $this->sendMessage($chat_id, 'Вы заблокированы и не можете отправлять сообщения.');
            return;
        }

        if ($user->status == 'active') {
            if ($this->isValidMessage($text)) {
                Message::create([
                    'message' => $text,
                    'telegram_id' => $user->telegram_id,
                ]);

                $this->sendMessage($chat_id, 'Ваше сообщение сохранено.');
            } else {
                $this->sendMessage($chat_id, 'Сообщение не прошло проверку. Пожалуйста, проверьте длину сообщения.');
            }
        }
    }

    private function isValidPhone($phone)
    {
        return preg_match('/^\+?[1-9]\d{1,14}$/', $phone);
    }

    private function isValidMessage($message)
    {
        return strlen($message) > 0 && strlen($message) <= 50;
    }

    private function sendMessage($chat_id, $text)
    {
        $bot = TelegraphBot::first();
        if ($bot) {
            $chat = $bot->chats()->where('chat_id', $chat_id)->first();
            $chat->message($text)->send();
        } else {
            Log::error('No bot found in database');
        }
    }
}
