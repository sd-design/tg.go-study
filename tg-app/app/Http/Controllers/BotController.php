<?php
namespace App\Http\Controllers;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Http\Request;

class BotController extends Controller
{
    public function index(Request $request)
    {
        $data1 = ['application'=> 'Bot Telegram 1.0', 'json' => 'test', 'result' => 'ok', 'csrf' => $request->session()->token()];

        return $data1;
    }

    public function input(Request $request)
    {
        $response = $telegram->sendMessage([
            'chat_id' => '123456789',
            'text' => 'Hello World'
        ]);
         return $response;

       /* $name = $request->input('name');
        return $name;*/
    }


}
