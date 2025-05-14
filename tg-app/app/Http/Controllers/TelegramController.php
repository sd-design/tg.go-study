<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    /**
     * Обрабатывает входящие обновления от Telegram через webhook.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function handleWebhook(Request $request)
    {

        try {
            // Получаем обновления от Telegram
            $update = Telegram::getWebhookUpdate();
            $commandMode = 0;

            //Не забыть отключить логирование!!!!!
            //Log::info('Telegram Update: ' . json_encode($update->toArray()));



            // Проверяем, есть ли сообщение
          if ($update->isType('message')) {
                $chatId = $update->getMessage()->getChat()->getId();
                $text = $update->getMessage()->getText();
              //$response = Telegram::getMe(); //Bot Indetificator

                $firstName = $update->getMessage()->getChat()->getFirstName();
                $lastName = $update->getMessage()->getChat()->getLastName();
                $userName = $update->getMessage()->getChat()->getUsername();

                             // Логика обработки сообщения
                             switch (strtolower($text)) {
                                 case '/start':
                                     $responseText = '<b>Что может делать этот бот?</b>
Бот-помощник для оперативного информирования о заявках на сайте. <tg-emoji emoji-id="5368324170671202286">👍</tg-emoji>';
                                     break;
                                 case '/menu':
                                     $this->sendKeyboardStart($chatId);
                                     $commandMode = 1;
                                     break;
                                 case '/add_me':
                                     $responseText = 'Data: '. $firstName. " | ". $lastName . " | ". $userName ;
                                     break;
                                 case '/help':
                                     $responseText = 'Доступные команды:
                                     /start
                                     /menu
                                     /add_me
                                     /help';
                                     break;
                                 default:
                                     $responseText = "<b>Вы написали</b>: " ."<blockquote>" .$text. "</blockquote>";
                             }

            //$responseText = this->checkCommand($text);

                // Отправляем ответ пользователю
                if($commandMode === 0){
                    Telegram::sendMessage([
                        'chat_id' => $chatId,
                        'text' => $responseText,
                        'parse_mode' => 'HTML', // Поддержка форматирования
                    ]);
                }


                // Отправка сообщения с клавиатурой
               // $this->sendKeyboard($chatId);
            }

            //Обработка инлайн-кнопок
            if ($update->isType('callback_query')) {
                $callbackQuery = $update->getCallbackQuery();
                $chatId = $callbackQuery->getMessage()->getChat()->getId();
                $data = $callbackQuery->getData();

                $this->insert_action($chatId, $data);
                // Ответ на нажатие кнопки
                $responseText = " Вы нажали: " . $data . "chatID: ".$chatId;

                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => $responseText,
                ]);

                // Подтверждаем callback-запрос
               /*Telegram::answerCallbackQuery([
                    'callback_query_id' => $callbackQuery->getId(),
                    'text' => 'Действие выполнено!',
                    'show_alert' => false,
                ]);*/
            }

            // Возвращаем статус 200, чтобы Telegram знал, что запрос обработан
            return response()->json(['status' => 'success'], 200);
        } catch (TelegramSDKException $e) {
            // Логируем ошибку
            \Log::error('Telegram Bot Error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Отправляет сообщение с инлайн-клавиатурой в ответ на команду Start.
     *
     * @param int $chatId
     * @return void
     */
    private function sendKeyboardStart($chatId)
    {
        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => 'запросить контакт', 'callback_data' => 'buttonContact'],
                    ['text' => 'подключиться как менеджер', 'callback_data' => 'buttonAddManager'],
                ],
            ],
        ];

        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => '<b>Выберите опцию:</b>',
            'parse_mode' => 'HTML',
            'reply_markup' => json_encode($keyboard),
        ]);
    }

    /**
     * Отправляет сообщение с инлайн-клавиатурой.
     *
     * @param int $chatId
     * @return void
     */
    private function sendKeyboard($chatId)
    {
        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => 'Кнопка 1', 'callback_data' => 'button1'],
                    ['text' => 'Кнопка 2', 'callback_data' => 'button2'],
                ],
            ],
        ];

        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => 'Выберите опцию:',
            'reply_markup' => json_encode($keyboard),
        ]);
    }

    /**
     * Тестовый метод для проверки бота.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function testBot()
    {
        try {
            $response = Telegram::getMe();
            return response()->json($response);
        } catch (TelegramSDKException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function insert_action($chatId, $action)
    {
        $session = DB::table('sessions')->where('chat_id', $chatId)->first();

        if(isset($session->status)){
            return $session->status;
        }
        else{
            DB::table('sessions')->insert([
                'chat_id' => $chatId,
                'status' => $action
            ]);
            //return response("No session yet", 400);
        }
    }

    public function add_new_manager($chatId, $action)
    {
        $manager = DB::table('managers')->where('chat_id', $chatId)->first();

        if(isset($session->role)){
            return $session->role;
        }
        else{
            DB::table('managers')->insert([
                'chat_id' => $chatId,
                'first_name' => $action,
                'last_name' => $action,
                'username' => $action
            ]);
            //return response("No session yet", 400);
        }
    }

}
