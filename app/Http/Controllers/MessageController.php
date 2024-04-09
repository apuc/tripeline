<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Models\Message;

class MessageController extends Controller {

    public function add(Request $request)
    {
        date_default_timezone_set('UTC');

        $message = Message::create([
            'chat_id' => $request->chat_id,
            'user_id' => $request->user_id,
            'subject' => '',
            'message' => $request->message,
            'status' => 0,
        ]);

        return json_encode([
            'status' => true,
            'message' => $request->message,
            'date' => $message->created_at
        ]);
    }

}
