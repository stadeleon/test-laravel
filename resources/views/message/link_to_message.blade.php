@extends('layouts.app')
@section('content')
<div>
    <h1>{!! link_to_route("message.show", 'Link to your secret message', [$link]) !!}</h1>
</div>
@stop