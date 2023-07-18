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

                        <div class="card-header">
                            <h3>Select company and make an event!</h3>
                            <div class="card-body">
                                <div class="card-body">
                                    @if ($companies->isEmpty())
                                        <p>No companies available for this type.</p>
                                    @else
                                        <h3>Available companies:</h3>
                                        <ul>
                                            @foreach ($companies as $company)
                                                <li>{{ $company->companyName }}</li>
                                            @endforeach
                                        </ul>
                                        <form action="{{ route('events.store') }}" method="post" id="eventForm">
                                            @csrf
                                            <input type="hidden" name="type_id" value="{{ session('selected_type') }}">
                                            @foreach ($companies as $company)
                                                <input type="radio" name="company_id" value="{{ $company->id }}" required> {{ $company->companyName }}<br>
                                            @endforeach
                                            <label for="event_name">Event Name:</label>
                                            <input type="text" name="event_name" required>

                                            <label for="event_start">Event Date:</label>
                                            <input type="date" name="event_start" required>

                                            <button type="submit">Create Event</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
