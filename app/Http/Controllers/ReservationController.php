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

    public function index()
    {
        $user = Auth::user(); 
        
        if (!$user) {
            return response()->json(['message' => 'Please sign in first'], 401);
        }

        $reservations = Reservation::where('user_id', $user->id)
                       ->orderBy('appointment_date', 'desc')
                       ->get(); 
        
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
        $reservation->save();
    
        return response()->json($reservation, 201);
    }
}
