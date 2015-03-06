<?php namespace App\Http\Controllers;

use App\Conversation;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Message;
use App\User;
use Collective\Annotations\Routing\Annotations\Annotations\Middleware;
use Illuminate\Http\Request;

/**
 * Class MessageController
 *
 * @Controller(prefix="conversation/{conversationId}")
 * @Middleware("auth")
 *
 * @package App\Http\Controllers
 */
class MessageController extends Controller {

    /**
     * @var Conversation
     */
    private $conversation;
    /**
     * @var Message
     */
    private $message;
    /**
     * @var User
     */
    private $user;

    public function __construct(Conversation $conversation, Message $message, User $user)
    {
        $this->conversation = $conversation;
        $this->message = $message;
        $this->user = $user;
    }

    /**
     * @Get("/", as="conversations.messages.show")
     */
    public function show($conversationId, Request $request)
    {
        $currentUser = $request->user();

        $messages = $this->message->whereHas('conversations', function($q) use ($conversationId) {
            $q->whereId($conversationId);
        })->whereHas('users', function($q) use ($currentUser) {
            $q->whereId($currentUser->id);
        })->with(['conversations', 'users'])->get();

        $conversation = $this->conversation->with(['users' => function($q) {
            $q->wherePivot('readonly', '0');
        }])->find($conversationId);

        return view()->make('messages.list', compact('messages', 'conversation'));
    }

    /**
     * @Post("send", as="conversations.message.send")
     * @param $conversationId
     */
    public function send($conversationId, Request $request)
    {
        $this->validate($request, ['message' => 'required']);

        $data = [
            'conversation_id' => $conversationId,
            'user_id' => $request->user()->id,
            'body' => $request->message
        ];

        $message = $this->message->create($data);
        $conversation = $this->conversation->with(['users' => function($q) {
            $q->whereReadonly('0');
        }])->find($conversationId);

        if(!$conversation->users->contains($request->user()->id)) {
            return redirect('logout');
        }

        $usersWhoCanRead = $conversation->users;
        $userId = [];
        foreach($usersWhoCanRead as $user) {
            $userId[] = $user->id;
        }

        $message->users()->attach($userId);

        return redirect()->back();
    }

}
