<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public function getAllValidMessages() {
        $messages = Message::latest('created_at')
            ->valid()
            ->get();

        return $messages;
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
