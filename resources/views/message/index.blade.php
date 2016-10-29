@extends('layouts.app')
@section('content')
    <div>
        {!! link_to_route('messages', 'valid') !!}
        {!! link_to_route('messages.invalid', 'invalid') !!}
        {!! link_to_route('message.create', 'create message') !!}
    </div>
    @foreach($messages as $message)
        <message>
            <h2>{!! $message->enc_key !!}</h2>
            <p>
                {{ $message->message }}
            </p>
            <p>
                created: {{ $message->created_at }}
            </p>
        </message>
    @endforeach
@stop