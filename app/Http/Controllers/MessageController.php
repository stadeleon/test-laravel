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
        $err = false;
        $status = 1;

        $messageRow = $messageModel->getValidMessageByKey($link);
        if (!$messageRow) {
            return view('message.not_existent_link');
        }
        $isExpired = MessageHelper::isMessageExpired($messageRow);
        if ($isExpired) {
            $flash = array('type' => 'danger', 'message' => 'WARNING THIS Message is unavailable because of timeout expired');
            $view = 'message.not_existent_link';
        }

        $data['link']   = $link;
        $key = $request->password;
        if (empty($key) && !$err) {
            $err = true;
        }

        if(!$err && 'instantly' == $messageRow->destruct_type) {
            $status = 0;
            $flash = array('type' => 'danger', 'message' => 'Link will be destroyed after opening');
        } else if (!$isExpired){
            $flash = ['type' => 'info', 'message' => 'Enter password'];
        }

        if (!$err && !empty($request->password) && !Hash::check($key, $messageRow->password_hash)) {
            $err = true;
            $flash = array('type' => 'danger', 'message' => 'Incorrect password');
        }

        if (!$err && $isExpired) {
            $status = 0;
            $messageModel->updateMessageStatus($messageRow, $status);
            $view = 'message.not_existent_link';
        } else if (!$isExpired && $err) {
            $view = 'message.authorize_message';
        }

        if ($err || $isExpired) {
            \Session::flash('flash_message', $flash);
            return view($view, $data);
        }

        if ('instantly' == $messageRow->destruct_type && !$err) {
            $status = 0;
            \Session::flash('flash_message', array('type' => 'danger', 'message' => 'THIS Message will be UNAVAILABLE after page refreshing'));
        }

        $encryptor = new MyEncryptor($key);
        $data = ['message' => $encryptor->decryptMessage($messageRow->message)];

        if (0 == $status) {
            $messageModel->updateMessageStatus($messageRow, $status);
        }

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
