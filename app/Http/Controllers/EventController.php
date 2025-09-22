<?php
namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
public function index() {
$events = Event::all();
return view('events.index', compact('events'));
}

public function create() {
return view('events.create');
}

public function store(Request $request) {
$request->validate([
'title' => 'required|string',
'date' => 'required|date',
'location' => 'required|string',
'seats' => 'required|integer',
]);

Event::create($request->only('title','date', 'location','seats'));
return redirect()->route('events.index')->with('success','Event created!');
}

public function edit(Event $event) {
return view('events.edit', compact('event'));
}

public function update(Request $request, Event $event) {
$request->validate([
'title' => 'required|string',
'date' => 'required|date',
'location' => 'required|string',
'seats' => 'required|integer',
]);

$event->update($request->only('title', 'date', 'location', 'seats'));
return redirect()->route('events.index')->with('success','Event updated!');
}

public function destroy(Event $event) {
$event->delete();
return redirect()->route('events.index')->with('success','Event deleted!');
}
}
