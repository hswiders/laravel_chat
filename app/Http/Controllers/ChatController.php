<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use Pusher\Pusher;

class ChatController extends Controller
{
    public function index()
    {
        $chats = Chat::all();

        return view('chat' ,compact('chats'));
    }
    public function chat()
    {
        return view('chat');
    }

    public function sendMessage(Request $request)
    {
        $chat = new Chat();
        $chat->user = $request->input('user');
        $chat->message = $request->input('message');
        $chat->save();

        $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), [
            'cluster' => env('PUSHER_APP_CLUSTER')
        ]);

        $data = [
            'user' => $chat->user,
            'message' => $chat->message,
            'created_at' => $chat->created_at->toDateTimeString()
        ];

        $pusher->trigger('chat-channel', 'send-message', $data);

        return response()->json([
            'message' => 'Message sent!'
        ]);
    }

    public function deleteMessage($id)
    {
        $chat = Chat::findOrFail($id);
        $chat->delete();

        return response()->json([
            'message' => 'Message deleted!'
        ]);
    }
}
