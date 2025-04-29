<?php
namespace App\Http\Controllers;
use Telegram\Bot\Laravel\Facades\Telegram;

class BotController extends Controller
{
    public function index()
    {
        $data1 = ['application'=> 'Bot Telegram 1.0', 'json' => 'test', 'result' => 'ok'];

        return $data1;
    }

    public function input()
    {
        $response = $telegram->sendMessage([
            'chat_id' => '123456789',
            'text' => 'Hello World'
        ]);

        return $response;
    }


}
