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
                        {{ __('You are logged in as a user!') }}<br>
                        If you want to create a new company, click the link.<br>
                        <a href="{{ route('companies.create') }}">Create a new company</a>
                        <div class="card-header">
                            <div class="card-body">
                                @if ($user->companies->count() > 0)
                                    @foreach ($user->companies as $item)
                                        <h3>List of your companies and visit requests</h3>
                                        <p>Company Name: {{ $item->companyName }}</p>
                                        <p>Email: {{ $item->email }}</p>
                                        <p><a href="{{ route('company.details', $item->id) }}">Click here to view more details</a></p>
                                        <hr>

                                        <h4>Visit Requests to {{ $item->companyName }}</h4>
                                        @if ($item->visitRequests->count() > 0)
                                            @foreach ($item->visitRequests as $visitRequest)
                                                <p>User: {{ optional($visitRequest->user)->name }}</p>
                                                <p>Visit Date: {{ $visitRequest->visit_date }}</p>
                                                <p>Status: {{ $visitRequest->event_status }}</p>
                                                <form action="{{ route('checkout') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="visit_request_id" value="{{ $visitRequest->id }}">
                                                    <button type="submit">Pay for the visit</button>
                                                </form>
                                                <hr>
                                            @endforeach
                                        @else
                                            <p>No visit requests found.</p>
                                        @endif
                                    @endforeach
                                @else
                                    <p>You don't have any company.</p>
                                @endif
                                @foreach ($user->companies as $company)
                                    <a href="{{ route('send.visit.request', ['companyId' => $company->id]) }}">Send Visit Request for {{ $company->companyName }}</a>
                                    <hr>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
