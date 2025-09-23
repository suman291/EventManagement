@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header text-center bg-primary text-white text-center">Create Event</div>
                <div class="card-body">
                        <form method="POST" action="{{ route('bookings.store') }}" class="needs-validation" novalidate>
                            @csrf

                            <input type="hidden" name="user_id" class="form-control" value="{{auth()->user()->id}}" required>
                            <button type="submit" class="btn btn-success w-100">Book</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endsection
