{{--@extends('layouts.app')--}}
@section('content')
    <h1>
        {!! Form::open(['route' => array("message.show", $link), 'id' => 'auth_form', 'method' => 'post']) !!}
        @include('message.forms._password_form')
        {!! Form::close() !!}
    </h1>

@stop