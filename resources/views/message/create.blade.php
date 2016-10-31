@extends('layouts.app')
@section('content')
    <h1>
        {!! Form::open(['route' => 'message.store', 'id' => 'creation_form']) !!}
        @include('message.forms._add_form')
        {!! Form::close() !!}
    </h1>
    <div id="link_to_shared_message"></div>
    <script>
        $(document).ready(function($){
            $('.creation_form-form').on('submit', function(e){
                e.preventDefault();
                _this = $(this);

                _this.find('.active').addClass('working');

                var approve_url = $('input[name="approve_url"]').val();
                var data = $(this).serialize();

                $.ajax({
                            type: 'POST',
                            headers: {'X-CSRF-Token': token},
                            url: approve_url,
                            data: data,
                        })
                        .success(function(){
                            _this.find('button.working').removeClass('working');
                            _this.find('.btn').toggleClass('active');
                            $('#link_to_shared_message').html(data.content);
                        })
                        .error(function(data){
                        });
            });
        });
    </script>
@stop