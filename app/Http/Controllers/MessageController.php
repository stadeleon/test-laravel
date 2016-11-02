<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Http\Middleware\MessageHelper;
use App\Http\Middleware\MyEncryptor;
use Illuminate\Support\Facades\Hash;

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
        $key     = $request->password;
        $message = $request->message;
        $link    = md5(uniqid(rand(),true));

        $encryptor        = new MyEncryptor($key);
        $passwordHash     = Hash::make($key);
        $encryptedMessage = $encryptor->encryptMessage($message);

        $data = [
            'link'          => $link,
            'message'       => $encryptedMessage,
            'password_hash' => $passwordHash,
            'destruct_type' => $request->destruct_type,
            'time_to_live'  => $request->time_to_live
        ];

        $message = $messageModel->create($data);
        if ($message->id) {
            return view('message.link_to_message', ['link' => $link]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Message $messageModel, $link, Request $request)
    {
        $messageRow = $messageModel->getValidMessageByKey($link);
        if (!$messageRow) {
            return view('message.not_existent_link');
        }

        if('instantly' == $messageRow->destruct_type) {
            $notice = "Link will be destroyed after opening";
        } else {
            $notice = "Link will be destroyed in";
        }
        $data['notice'] = $notice;
        $data['link']   = $link;
        $data['err']    = false;
        $key = $request->password;
        if(empty($key)) {
            $data['err'] = true;
            $flashMessage = 'Enter password';
        }
        if(!empty($request->password) && !Hash::check($key, $messageRow->password_hash)) {
            $data['err'] = true;
            $flashMessage = 'Incorrect password';
        }
        if ($data['err']) {
            \Session::flash('flash_message', ['type' => 'info', 'message' => $flashMessage]);
            return view('message.authorize_message', $data);
        }

        $status = MessageHelper::updateMessageStatus($messageRow);
        $encryptor = new MyEncryptor($key);
        $data = ['message' => $encryptor->decryptMessage($messageRow->message)];
        return view('message.show', $data);


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
