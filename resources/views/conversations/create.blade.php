@extends('app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Konversation starten</div>
                    <div class="panel-body">
                        @include('partials/_errors')
                        {!! Form::open() !!}
                            <div class="form-group">
                                {!! Form::label('Name der Konversation') !!}
                                {!! Form::text('name', null, ['class' => 'form-control']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('Users') !!}
                                {!! Form::select('users[]', $users, null, ['class' => 'form-control', 'multiple']) !!}
                            </div>
                            {!! Form::submit('Starten', ['class' => 'btn btn-primary']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop