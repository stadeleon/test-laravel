
<div class="form-group">
    {!! Form::label('Enter secret message to send') !!}
    {!! Form::textarea('message', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('destruct_type') !!}
    {!! Form::select('destruct_type', ['instantly' => 'instantly', 'timeout' => 'timeout']) !!}
</div>
<div class="form-group">
    {!! Form::label('enter password to encrypt message') !!}
    {!! Form::text('password', null, array('placeholder' => 'max 32 symbols','maxlength' => 32 )) !!}
</div>
<div class="form-group">
    {!! Form::label('created at') !!}
    {!! Form::input('date', 'created_time', date('Y-m-d'), ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::submit('Create', ['class' => 'btn btn-primary']) !!}
</div>