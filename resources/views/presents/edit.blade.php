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
                        {{ __('Edit present!') }}<br>
                            <div>
                                <form action="{{ route('presents.update', ['id' => $gift->id]) }}" method="post">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" name="title" id="title" class="form-control" value="{{ $gift->title }}">
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control">{{ $gift->description }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="necessary_requirements">Necessary Requirements (optional)</label>
                                    <textarea name="necessary_requirements" id="necessary_requirements" class="form-control">{{ $gift->necessary_requirements }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="optional_requirements">Optional Requirements (optional)</label>
                                    <textarea name="optional_requirements" id="optional_requirements" class="form-control">{{ $gift->optional_requirements }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="links">Example Links</label>
                                    <input type="text" name="links" id="links" class="form-control" value="{{ $gift->links }}">
                                </div>

                                <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
