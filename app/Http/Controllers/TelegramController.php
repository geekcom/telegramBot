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

    public function getLatestActivities(Request $request)
    {
        $data = $request->all();

        $response = $this->guzzleHttp->post(env('TELEGRAM_API_URL') . env('TELEGRAM_BOT_TOKEN') . '/' . env('TELEGRAM_GET_UPDATES'), [
            'json' => [
                'chat_id' => $data['chat_id']
            ]
        ]);

        $jsonResponse = json_decode($response->getBody());

        return response()->json(['status' => 'success', 'data' => ['activities' => $jsonResponse->result]], 200);
    }
}
