@extends('app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">Konversationen</div>
                    <div class="panel-body">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Users</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($user->conversations as $conversation)
                                <tr>
                                    <td>{{ $conversation->name }}</td>
                                    <td>{{ $conversation->users->count() }}</td>
                                    <td>
                                        <div class="pull-right">
                                            @if($conversation->user_id == \Auth::user()->id)
                                                <a href="{{ route('conversations.edit', [$conversation]) }}">Bearbeiten</a>
                                            @endif
                                            <a href="{{ route('conversations.messages.show', [$conversation]) }}">Verlauf lesen</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        @if($user->conversations->isEmpty())
                            <div class="alert alert-warning">
                                <h3>Keine Konversationen</h3>
                                <p>
                                    Es wurden noch keine Konversationen gestartet
                                </p>
                                <p>
                                    <a href="{{ url('conversations/create') }}" class="btn btn-primary">Neue Konversation starten</a>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">Archivierte Konservationen</div>
                    <div class="panel-body">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($archive->conversations as $conversation)
                                <tr>
                                    <td>{{ $conversation->name }}</td>
                                    <td>
                                        <div class="pull-right">
                                            <a href="{{ route('conversations.messages.show', [$conversation]) }}">Verlauf lesen</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        @if($archive->conversations->isEmpty())
                            <div class="alert alert-warning">
                                <h3>Keine Konversationen archiviert</h3>
                                <p>
                                    Es wurden noch keine Konversationen archiviert
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop