<?php
namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class EventController extends Controller
{
    // Custom method to fetch event details
    public function getEventDetails()
    {
        $events = Event::select('id', 'title', 'date','location','seats')->get();
        $result = $events->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'date' => $event->date,
                'location' => $event->location,
                'seats'    => $event->seats,
            ];
        });

        return response()->json([
            'data' => $result
        ]);
    }
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
try{
Event::create($request->only('title','date', 'location','seats'));
 return response()->json([
                'status' => 'success',
            ], 200);
        } catch (ValidationException $e) {
            // Handle validation exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'Validation Failed',
                'errors' => $e->errors()
            ], 422);
        }
        catch(QueryException $e)
        {
             return response()->json([
            'status' => 'error',
            'message' => 'Database error: '.$e->getMessage()
        ], 500);
        }
        catch(ModelNotFoundException $e)
        {
            return response()->json([
            'status' => 'error',
            'message' => 'Employee not found: '.$e->getMessage()
            ], 404);
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
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

    public function destroy(Event $event)
    {
        try {
            $event->delete();
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
