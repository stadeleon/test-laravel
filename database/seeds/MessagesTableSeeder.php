<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

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
        Message::create([
            'message' => 'test text',
            'link' => 'some key',
            'destruct_type' => 'instantly',
            'created_time' => Carbon::now(),
            'status' => true,
        ]);

        $secret = "секрет";
        $password = 'my_original pa$$word';

        $encryptedText = Crypt::encrypt($secret);
        $decryptedText = Crypt::decrypt($encryptedText);

        Message::create([
            'message' => $encryptedText,
            'link' => $decryptedText,
            'destruct_type' => 'instantly',
            'created_time' => Carbon::now(),
            'status' => true,
        ]);

//        Crypt::setKey($password);
        $encryptedTextWithPass = Crypt::encrypt($secret);
//        Crypt::setKey(Config::get('app.key'));

        $decryptedTextWithPass = Crypt::decrypt($encryptedTextWithPass);

        Message::create([
            'message' => $encryptedTextWithPass,
            'link' => $decryptedTextWithPass,
            'destruct_type' => 'instantly',
            'created_time' => Carbon::now(),
            'status' => false,
        ]);


//        Crypt::setKey($password);
//        $encryptedTextWithPass = Crypt::encrypt($secret);
//        $decryptedTextWithPass = Crypt::decrypt($encryptedTextWithPass);

        Message::create([
            'message' => $encryptedText,
            'link' => $decryptedText,
            'destruct_type' => 'timeout',
            'created_time' => Carbon::now(),
            'status' => true,
        ]);
    }
}
