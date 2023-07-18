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
                            <h3>Select company and make a event!</h3>
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
                                    @endif

                                    @if(isset($companies) && count($companies) > 0)
                                        <form id="eventForm" method="POST">
                                            @csrf <!-- Dodaj to @csrf -->
                                            <label for="event_name">Event Name:</label>
                                            <input type="text" name="event_name" required>

                                            <label for="event_start">Event Date:</label>
                                            <input type="date" name="event_start" required>

                                            <label for="company_id">Select Company:</label>
                                            <select name="company_id" id="company_id" required>
                                                <option value="">Select a company</option>
                                                @foreach ($companies as $company)
                                                    <option value="{{ $company->id }}">{{ $company->companyName }}</option>
                                                @endforeach
                                            </select>

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