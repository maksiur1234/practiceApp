@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Select event to send a gift!') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                            <div>
                                @foreach($events as $event)
                                    Available events:
                                    <li>Event name: {{ $event->name }}</li>
                                    <li>Event status: {{ $event->status }}</li>
                                    <li>Event date: {{ $event->date }}</li>
                                    <a href="{{ route('presents.create', ['eventId' => $event->id]) }}">Create Gift for this event!</a>

                                @endforeach
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection
