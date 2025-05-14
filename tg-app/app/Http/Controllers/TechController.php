<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Blade;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;


class TechController extends Controller
{
    public function phpinfo()
    {
        return phpinfo();

    }

    public function putSession(Request $request)
    {
        $request->session()->put('client_type', 'user');
        return "put to session";

    }

    public function getSession(Request $request)
    {
        $client = $request->session()->get('client_type');
        return $client;

    }

    public function encrypt(Request $request)
    {
        $hash = Crypt::encryptString($request->token);
        return $hash;

    }

    public function decrypt(Request $request)
    {
        if ($request->has(['hash'])) {
            try {
                $token = Crypt::decryptString($request->hash);
                return $token;
            }catch (Throwable $e) {
                report($e);

                return false;
            }
        }
        else{
            return response("No hash string", 400);
        }


    }


    public function json_test()
    {
        $data = ['application'=> 'ver 1.0', 'json' => 'test', 'result' => 'ok'];

        return $data;
    }

    public function db_test()
    {
        $options =  DB::table('options')->get();

        foreach ($options as $option) {
            $data = ['Name'=> $option->name, 'switch' => $option->switch, 'value' => $option->value];
            return $data;
        }
    }

    public function insert_action()
    {
        $chatId =  '150625076';
        $session = DB::table('sessions')->where('chat_id', $chatId)->first();

            if(isset($session->status)){
                return response(["status"=> $session->status], 200);
            }
            else{
                return response("No session yet", 400);
            }

    }

}
