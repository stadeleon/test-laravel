<?php

namespace App\Http\Controllers;

use App\Http\Middleware\MyEncryptor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Message;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Message $messageModel)
    {
        $data['messages'] = $messageModel->getAllValidMessages();

        return view('message.index', $data);
    }

    public function invalid(Message $messageModel) {
        $data['messages'] = $messageModel->getInvalidMessages();
        return view('message.index', $data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('message.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Message $messageModel, Request $request)
    {
        $message = $request->message;
        $key = "mypassword\0\0\0\0\0\0";
        $encryptor = new MyEncryptor($key);

        $encryptedMessage = $encryptor->encryptMessage($message);


        $link = md5(uniqid(rand(),true));

        $data = [
            'message' => $encryptedMessage,
            'enc_key' => $link,
            'destruct_type' => $request->destruct_type,
            'status' => $request->status ? true : false,
            'created_time' => $request->created_time
        ];

//        if($request->ajax()) // This is what i am needing.
//        {
        $id = $messageModel->insertGetId($data);
        if ($id) {
            $key = md5($id);

            $linkPartOne = substr($key, 0, 10);
            $linkPartTwo = substr($key, 10);

            $link = url('/message') . '/' . $link;
            return view('message.link_to_message', ['link' => $link]);
        }
//        }

//        return redirect()->route('messages');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Message $messageModel, $key)
    {
        $messageRow = $messageModel->getValidMessageByKey($key);
        if ($messageRow) {
            $key = "mypassword\0\0\0\0\0\0";
            $encryptor = new MyEncryptor($key);

            $data = ['message' => $encryptor->decryptMessage($messageRow->message)];
            return view('message.show', $data);
        }
        return view('message.not_existent_link');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
