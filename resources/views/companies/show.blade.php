@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard for company') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="card-header">
                            <h3>List of requests for your companies, click the link for details to see visit requests.</h3>
                            <div class="card-body">
                                <div class="card-body">
                                    @foreach ($user->companies as $company)
                                        <div class="card">
                                            <div class="card-header">
                                                <h3>{{ $company->name }}</h3>
                                            </div>
                                            <div class="card-body">
                                                <p>Email: {{ $company->email }}</p>
                                                <p>
                                                    <a href="{{ route('company.visit.requests', ['companyId' => $company->id]) }}">
                                                        Click here to view visit requests
                                                    </a>
                                                </p>
                                            </div>
                                            <div class="card-footer">
                                                <form action="{{ route('upload.media', ['companyId' => $company->id]) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="media">Upload Media:</label>
                                                        <input type="file" name="media" id="media" class="form-control-file">
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Upload</button>
                                                </form>
                                            </div>
                                        </div>
                                        <br>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
