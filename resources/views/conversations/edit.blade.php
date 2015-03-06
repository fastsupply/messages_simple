@extends('app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Konversation starten</div>
                    <div class="panel-body">
                        @include('partials/_errors')
                        {!! Form::model($conversation) !!}
                            <div class="form-group">
                                {!! Form::label('Name der Konversation') !!}:
                                <b class="form-control-static">{{ $conversation->name }}</b>
                            </div>
                            <div class="form-group">
                                {!! Form::label('Users') !!}
                                {!! Form::select('users[]', $users, $selectedUsers, ['class' => 'form-control', 'multiple']) !!}
                            </div>
                            {!! Form::submit('Starten', ['class' => 'btn btn-primary']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop