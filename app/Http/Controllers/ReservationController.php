<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $user = Auth::user(); 
        
        if (!$user) {
            return response()->json(['message' => 'Please sign in first'], 401);
        }

        $query = $request->query('q');

        $per_page = $request->input('per_page', 5);

        if($user->role === 'admin') {
            $reservationsQuery = Reservation::query();
        }else {
            $reservationsQuery = Reservation::where('user_id', $user->id);
        }

        if ($query) {
            $reservationsQuery -> where('description', 'LIKE', '%'.$query.'%');
        }
        
        $reservations = $reservationsQuery->orderBy('appointment_date', 'desc')->latest()->paginate($per_page); 
        
        if ($reservations->isEmpty()) {
            return response()->json(['message' => 'No reservations created by this user'], 404);
        }
        
        return response()->json($reservations);
    }

    public function show($id)
    {
        $user = Auth::user();

        $reservation = Reservation::find($id);
        if ($reservation) {
            if ($reservation->user_id !== $user->id) {
                return response()->json(['message' => 'Cannot show reservation'], 403);
            }
            return response()->json($reservation);
        } else {
            return response()->json(['message' => 'Reservation not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $user = Auth::user();
    
        $data = $request->validate([
            'description' => 'required|string',
            'contact' => 'required|string',
            'appointment_date' => 'required|date',
            'client_name' => 'required|string',
            'services' => 'required|string'
        ]);
    
        $reservation = new Reservation($data);
        $reservation->user_id = $user->id;
        if($reservation->save()){
            return response()->json(['data' => 'Reservation was added successfully', 'status' => 'success'], 200);
        } else {
            return response()->json(['error' => 'Case was not created.'], 500);
        }
    
        return response()->json($reservation, 201);
    }
}
