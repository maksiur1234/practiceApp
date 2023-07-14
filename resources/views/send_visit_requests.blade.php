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
                            <h3>Select company and request for a visit.</h3>
                            <div class="card-body">
                                <div class="card-body">
                                    <form action="{{ route('send.visit.request', ['companyId' => $company->id]) }}" method="POST">
                                    @csrf
                                        <div class="form-group">
                                            <label for="company_id">Select Company:</label>
                                            <select name="company_id" id="company_id" class="form-control">
                                                @foreach ($companies as $company)
                                                    <option value="{{ $company->id }}">{{ $company->companyName }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="visit_date">Visit Date:</label>
                                            <input type="date" name="visit_date" id="visit_date" class="form-control">
                                        </div>

                                        <button type="submit" class="btn btn-primary">Send Request</button>
                                    </form>
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
