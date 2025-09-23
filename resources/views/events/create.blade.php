@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header text-center bg-primary text-white text-center">Create Event</div>
                <div class="card-body">
                        <form  id="form_saveEvent" class="needs-validation" novalidate onsubmit="return saveEvent(this)">
                            <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" required>
                            </div>
                            <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" name="date" class="form-control" required>
                            </div>
                            <div class="mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" class="form-control" required>
                            </div>

                            <div class="mb-3">
                            <label class="form-label">Seats</label>
                            <input type="number" name="seats" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endsection
