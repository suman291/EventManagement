<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all bookings for the currently authenticated user

        $events= Event::all();
        return view('bookings.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     * This method is not typically used for direct booking but can be for a
     * dedicated booking page where a user selects an event from a list.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $events = Event::where('date', '>=', now())->get();
        return view('bookings.create', compact('events'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validate the incoming request
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'user_id' => 'required|exists:users,id',
        ]);

        // 2. Find the event
        $event = Event::findOrFail($request->event_id);
        // 3. Check for available seats
        if ($event->seats <= 0) {
            return redirect()->route('bookings.index')->with('error', 'Sorry, this event is fully booked.');
        }

        // 4. Check if the user has already booked this event
        if (Auth::user()->bookings()->where('event_id', $event->id)->exists()) {
            return redirect()->route('bookings.index')->with('error', 'You have already booked this event.');
        }


        // 5. Create the booking
        $booking = new Booking();
        $booking->user_id = Auth::id();
        $booking->event_id = $event->id;
        $booking->save();

        // 6. Decrement the seats count on the event
        $event->decrement('seats');

        return redirect()->route('bookings.index')->with('success', 'Event booked successfully!');
    }
    public function myBookings(){
        $bookings = Auth::user()->bookings()->with('event')->get();
        return view('bookings.myBookings',compact('bookings'));
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\View\View
     */
    public function show(Booking $booking)
    {
        // Ensure the authenticated user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        return view('bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     * Bookings are not typically editable once created, they are only canceled.
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     * Bookings are not typically editable once created, they are only canceled.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $id)
    {
        return redirect()->route('bookings.index')->with('error', 'Bookings cannot be updated.');
    }

    /**
     * Remove the specified resource from storage.
     * This method cancels a booking and increments the event's seats.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Booking $booking)
    {
        // Ensure the authenticated user owns this booking
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Increment the seats on the related event
        $event = $booking->event;
        $event->increment('seats');

        // Delete the booking record
        $booking->delete();

        return redirect()->route('bookings.index')->with('success', 'Booking cancelled successfully.');
    }
}
