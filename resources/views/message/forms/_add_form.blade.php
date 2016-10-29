
<div class="form-group">
    {!! Form::label('slug') !!}
    {!! Form::text('slug', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('title') !!}
    {!! Form::text('slug', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('message') !!}
    {!! Form::textarea('message', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('is active') !!}
    {!! Form::checkbox('status', null, true) !!}
</div>
<div class="form-group">
    {!! Form::label('created at') !!}
    {!! Form::input('date', 'created_at', date('Y-m-d'), ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::submit('Create', ['class' => 'btn btn-primary']) !!}
</div>