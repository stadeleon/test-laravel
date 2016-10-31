<div class="form-group">
    {!! Form::label('enter password') !!}
    {!! Form::password('password', null, array('placeholder' => 'max 32 symbols','maxlength' => 32 )) !!}
</div>
<div class="form-group">
    {!! Form::submit('Open', ['class' => 'btn btn-primary']) !!}
</div>