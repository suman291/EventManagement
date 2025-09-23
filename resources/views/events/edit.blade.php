@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header text-center bg-primary text-white text-center">Update Event</div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('events.update', $event->id) }}" class="needs-validation" novalidate>
                    @csrf
                        @method('PUT')
                        <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" value="{{ $event->title}}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="date" value="{{ $event->date}}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" value="{{ $event->location}}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                        <label class="form-label">Seats</label>
                        <input type="number" name="seats" value="{{ $event->seats}}" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Update</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
