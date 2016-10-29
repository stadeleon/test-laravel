@extends('layouts.app')
@section('content')
    <h1>
        {!! Form::open(['route' => 'message.store']) !!}
        @include('message.forms._add_form')
        {!! Form::close() !!}
    </h1>
@stop