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
                            @if ($user->companies->count() > 0)
                                @foreach ($user->companies as $company)
                                    <p>Company Name: {{ $company->companyName }}</p>
                                    <p>Email: {{ $company->email }}</p>
                                    <p><a href="{{ route('company.details', $company->id) }}">Click here to view more details</a></p>
                                    <hr>
                                @endforeach
                            @else
                                <p>You don't have any company.</p>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
