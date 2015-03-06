<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model {

	protected $table = 'conversations';

    protected $fillable = [
        'name',
        'user_id'
    ];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'conversation_users')->withPivot('readonly');
    }

}
