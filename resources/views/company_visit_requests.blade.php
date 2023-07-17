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

                        {{ __('You are logged in as a company!') }}<br>
                        <div class="card-header">
                            <div class="card-body">
                                <div class="card-body">
                                    <div class="container">
                                        <h3>Visit Requests for {{ $company->companyName }}</h3>
                                        <hr>
                                        <div>
                                            @if ($visitRequests->count() > 0)
                                                @foreach ($visitRequests as $visitRequest)
                                                    <div class="visit-request">
                                                        <h4>{{ $visitRequest->user?->name }}</h4>
                                                        <p>{{ $visitRequest->visit_date }}</p>
                                                        <p>{{ $visitRequest->event_name }}</p>
                                                        <p>Status: {{ $visitRequest->event_status }}</p>
                                                        @if ($visitRequest->event_status === 'paid')
                                                            <form action="{{ route('visit.request.accept', ['eventId' => $visitRequest->id]) }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="eventId" value="{{ $visitRequest->id }}">
                                                                <button type="submit">Accept</button>
                                                            </form>

                                                            <form action="{{ route('visit.request.reject', ['eventId' => $visitRequest->id]) }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="eventId" value="{{ $visitRequest->id }}">
                                                                <button type="submit">Reject</button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                    <hr>
                                                @endforeach
                                            @else
                                                <p>No visit requests found.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
