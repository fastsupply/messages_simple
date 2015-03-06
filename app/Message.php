<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model {

	protected $table = 'messages';

    protected $fillable = [
        'conversation_id',
        'user_id',
        'body',
    ];

    public function conversations()
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'messages_users');
    }

}
