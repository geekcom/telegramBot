<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Api;
use GuzzleHttp\Client AS GuzzleClient;

class TelegramController extends Controller
{
    private $telegram;
    private $guzzleHttp;

    public function __construct()
    {
        $this->telegram = new Api();
        $this->guzzleHttp = new GuzzleClient();
    }

    public function sendMessages(Request $request)
    {
        $data = $request->all();

        $this->telegram->sendMessage([
            'chat_id' => $data['chat_id'],
            'text' => $data['text'],
        ]);

        return response()->json(['status' => 'success'], 200);
    }

    public function setWebHook(Request $request)
    {
        $data = $request->all();

        $this->telegram->setWebhook([
            'url' => $data['url'] . '/' . env('TELEGRAM_BOT_TOKEN') . '/webhook'
        ]);

        return response()->json(['status' => 'success'], 200);
    }

    public function removeWebHook(Request $request)
    {
        $data = $request->all();

        $this->telegram->removeWebhook([
            'url' => $data['url'] . '/' . env('TELEGRAM_BOT_TOKEN') . '/webhook'
        ]);

        return response()->json(['status' => 'success'], 200);
    }

    public function getChatMembersCount(Request $request)
    {
        $data = $request->all();

        $response = $this->guzzleHttp->post(env('API_URL') . env('TELEGRAM_BOT_TOKEN') . '/' . env('METHOD_API_GET_CHAT_MEMBERS'), [
            'json' => [
                'chat_id' => $data['chat_id']
            ]
        ]);

        $jsonResponse = json_decode($response->getBody());

        if ($jsonResponse->result >= 99) {
            return $this->sendMessageCountMembers();
        }
        return response()->json(['status' => 'success', 'data' => ['members' => $jsonResponse->result]], 200);
    }

    public function sendMessageCountMembers()
    {
        $this->telegram->sendMessage([
            'chat_id' => env('CHAT_ID'),
            'text' => 'Já temos mais de 98 pessoas no grupo, estamos de parabéns!',
        ]);

        return response()->json(['status' => 'success'], 200);
    }
}
