<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Message;
use App\Http\Middleware\MyEncryptor;
use Illuminate\Support\Facades\Hash;

class MessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('messages')->delete();

        $secret = "секрет";
        $key = 'my_original pa$$word';

        $link    = md5(uniqid(rand(),true));
        $encryptor        = new MyEncryptor($key);
        $passwordHash     = Hash::make($key);
        $encryptedMessage = $encryptor->encryptMessage($secret);

        Message::create([
            'message' => $encryptedMessage,
            'link' => $link,
            'password_hash' => $passwordHash,
            'destruct_type' => 'instantly',
            'time_to_live' => '300',
            'status' => true,
        ]);

        $secret = "секрет 2";
        $key = 'my pa$$word';

        $link    = md5(uniqid(rand(),true));
        $encryptor        = new MyEncryptor($key);
        $passwordHash     = Hash::make($key);
        $encryptedMessage = $encryptor->encryptMessage($secret);

        Message::create([
            'message' => $encryptedMessage,
            'link' => $link,
            'password_hash' => $passwordHash,
            'destruct_type' => 'instantly',
            'time_to_live' => '100',
            'status' => true,
        ]);

        $link    = md5(uniqid(rand(),true));
        $encryptor        = new MyEncryptor($key);
        $passwordHash     = Hash::make($key);
        $encryptedMessage = $encryptor->encryptMessage($secret);

        Message::create([
            'message' => $encryptedMessage,
            'link' => $link,
            'password_hash' => $passwordHash,
            'destruct_type' => 'instantly',
            'time_to_live' => '200',
            'status' => false,
        ]);

        $link    = md5(uniqid(rand(),true));
        $encryptor        = new MyEncryptor($key);
        $passwordHash     = Hash::make($key);
        $encryptedMessage = $encryptor->encryptMessage($secret);

        Message::create([
            'message' => $encryptedMessage,
            'link' => $link,
            'password_hash' => $passwordHash,
            'destruct_type' => 'timeout',
            'time_to_live' => '500',
            'status' => true,
        ]);
    }
}
