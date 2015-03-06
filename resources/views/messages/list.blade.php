@extends('app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('partials._errors')
            </div>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Konversation von {{ $conversation->name }}</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-9" style="border-right: 1px solid #ddd; height: 300px;">
                                <div class="messageWrapper" style="height: 230px; overflow: scroll;">
                                    <ul class="list-unstyled">
                                        @foreach($messages as $message)
                                            <li>{{ $message->body }} - {{ $message->created_at->diffForHumans() }}</li>
                                            <hr/>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="writer" style="margin-top: 15px;">
                                    @if($conversation->users->contains(Auth::user()->id))
                                        {!! Form::open(['route' => ['conversations.message.send', $conversation->id]]) !!}
                                            <div class="row">
                                                <div class="col-xs-10">
                                                    {!! Form::text('message', null, ['class' => 'form-control', 'placeholder' => 'Deine Nachricht', 'autofocus']) !!}
                                                </div>
                                                <div class="col-xs-2">
                                                    {!! Form::submit('Senden', ['class' => 'btn btn-primary btn-block']) !!}
                                                </div>
                                            </div>
                                        {!! Form::close() !!}
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <ul class="list-unstyled">
                                    @foreach($conversation->users as $user)
                                        <li>{{ $user->name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop