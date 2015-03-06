<?php namespace App\Http\Controllers;

use App\Conversation;
use App\Http\Requests;

use App\Message;
use App\User;
use Collective\Annotations\Routing\Annotations\Annotations\Middleware;
use Illuminate\Http\Request;

/**
 * Class ConversationController
 *
 * @Controller(prefix="conversations")
 * @Middleware("auth")
 *
 * @package App\Http\Controllers
 */
class ConversationController extends Controller {

    /**
     * @var Conversation
     */
    private $conversation;
    /**
     * @var User
     */
    private $user;
    /**
     * @var Message
     */
    private $message;

    public function __construct(Conversation $conversation, User $user, Message $message)
    {
        $this->conversation = $conversation;
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * @Get("/")
     * @param $id
     * @return mixed
     */
    public function show(Request $request)
    {
        $userId = $request->user()->id;
        $user = $this->user->with(['conversations' => function($q) {
            $q->wherePivot('readonly', '=', '0');
        }, 'conversations.users' => function($q) {
            $q->wherePivot('readonly', '=', '0');
        }])->find($userId);

        $archive = $this->user->with(['conversations' => function($q) {
            $q->wherePivot('readonly', '=', '1');
        }])->find($userId);

        return view()->make('conversations.list', compact('user', 'archive'));
    }

    /**
     * @Get("create")
     * @return mixed
     */
    public function create(Request $request)
    {
        $users = $this->user->where('id', '!=', $request->user()->id)->lists('name', 'id');
        return view()->make('conversations.create', compact('users'));
    }

    /**
     * @Post("create")
     * @param Request $request
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:conversations,name'
        ]);

        $conversation = $this->conversation->create([
            'name' => $request->name,
            'user_id' => $request->user()->id
        ]);

        $users = $request->users;
        $users = array_merge((array) $users, (array) $request->user()->id);

        $conversation->users()->attach($users);

        return redirect('conversations');
    }

    /**
     * @Get("{id}/edit", as="conversations.edit")
     * @param $id
     * @return mixed
     */
    public function showEdit($id, Request $request)
    {
        $conversation = $this->conversation->with('users')->find($id);
        $users = $this->user->where('id', '!=', $request->user()->id)->lists('name', 'id');

        $selectedUsers = [];

        foreach($conversation->users as $user) {
            if($user->pivot->readonly == '0') {
                $selectedUsers[] = $user->id;
            }

        }

        return view()->make('conversations.edit', compact('conversation', 'users', 'selectedUsers'));
    }

    /**
     * @Post("{id}/edit", as="conversations.update")
     * @param $id
     */
    public function doEdit($id, Request $request)
    {
        $users = array_merge((array) $request->users, [(string) $request->user()->id]);
        $conversation = $this->conversation->with('users')->find($id);

        foreach($conversation->users as $user) {
            foreach($users as $rUser) {
                if(!$conversation->users->contains((int) $rUser)) {
                    $conversation->users()->attach($rUser);
                }
                else {
                    if(!in_array($user->id, $users)) {
                        $conversation->users()->updateExistingPivot($user->id, ['readonly' => '1']);
                    }
                    else {
                        $conversation->users()->updateExistingPivot($user->id, ['readonly' => '0']);
                    }
                }
            }
        }

        return redirect('conversations');
    }

}
