<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';
    protected $fillable = ['id', 'message', 'link', 'password_hash', 'destruct_type', 'time_to_live', 'status', 'created_at', 'updated_at'];
    public function getAllValidMessages() {
        $messages = Message::latest('created_at')
            ->valid()
            ->get();
        return $messages;
    }

    public function getValidMessageByKey($key) {
        $message = Message::where(['link' => $key])
            ->valid()
            ->first();
        return $message;
    }
    public function getInvalidMessages() {
        $messages = Message::latest('created_at')
            ->invalid()
            ->get();

        return $messages;
    }

    public function scopeValid($query) {
        $query->where('status', true);
    }

    public function scopeInvalid($query) {
        $query->where('status', false);
    }
}
