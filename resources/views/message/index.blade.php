@extends('layouts.app')
@section('content')
    @foreach($messages as $message)
        <message>
            <h4>{!! link_to_route("message.show", 'Link to this message', [$message->link]) !!}</h4>
            <p>
                {{ $message->message }}
            </p>
            <p>
                created: {{ $message->created_at }}
            </p>
        </message>
    @endforeach
@stop