@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard for user') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <h2> <a href="{{ route('companies.create') }}">Create a new company</a><br></h2>
                        <h2><a href="{{ route('events.create') }}">Create a new event</a></h2>
                        <h2><a href="{{ route('choose.events') }}">Create a new present</a></h2>
                        <div class="card-header">
                            <div class="card-body">
                                @if ($user && $user->companies->count() > 0)
                                    <h2>List of your companies</h2>
                                    @foreach ($user->companies as $item)
                                        <p>Company Name: {{ $item->name }}</p>
                                        <p>Email: {{ $item->email }}</p>
                                        <p><a href="{{ route('company.details', $item->id) }}">Click here to view more details.</a></p>
                                        <hr>

                                        <h2>Event Requests to {{ $item->name }}</h2>
                                        @if ($item->visitRequests->count() > 0)
                                            @foreach ($item->visitRequests as $visitRequest)
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
                                @if ($user)
                                    @foreach ($user->companies as $company)
                                        <a href="{{ route('send.visit.request', ['companyId' => $company->id]) }}">Send Visit Request for {{ $company->name }}</a>
                                        <hr>
                                    @endforeach
                                @else
                                    <p>No companies to send visit request</p>
                                @endif
                                    <div class="card-body">
                                @if ($user)
                                    @foreach ($user->gifts as $gift)
                                                <h2>Your gifts: </h2>
                                        <div>
                                            <h4>Title: {{ $gift->title }}</h4>
                                            <p>Description: {{ $gift->description }}</p>
                                            <p>Necessary requirements: {{ $gift->necessary_requirements }}</p>
                                            <p>Optional requirements: {{ $gift->optional_requirements  }}</p>
                                            <p>Links: <a href="{{ $gift->links }}">{{ $gift->links }}</a></p>
                                        </div>
                                        <a href="{{ route('presents.edit', ['id' => $gift->id]) }}">Edit</a>

                                        <form action="{{ route('presents.destroy', ['id' => $gift->id]) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit">Delete</button>
                                        </form>
                                    @endforeach
                                @else
                                    <p>No gifts available for this user.</p>
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
