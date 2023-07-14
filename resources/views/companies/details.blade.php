@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('You are logged in!') }}<br>
                        If you want to create new company click the link.<br>
                        <a href="{{ route('companies.create') }}">Create new company</a>
                        <div class="card-header">
                            <h3>List of your companies</h3>
                            <div class="card-body">
                                @ <h4>User: {{ $event->user->name }}</h4>
                                <p>Visit Date: {{ $event->visit_date }}</p>
                                <p>Event Name: {{ $event->event_name }}</p>

                                <form method="POST" action="{{ route('visit.request.accept', $event->id) }}">
                                    @csrf
                                    <input type="hidden" name="company_id" value="{{ $event->company_id }}">
                                    <button type="submit" class="btn btn-success">Accept</button>
                                </form>

                                <form method="POST" action="{{ route('visit.request.reject', $event->id) }}">
                                    @csrf
                                    <input type="hidden" name="company_id" value="{{ $event->company_id }}">
                                    <button type="submit" class="btn btn-danger">Reject</button>
                                </form>

                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
@endsection
