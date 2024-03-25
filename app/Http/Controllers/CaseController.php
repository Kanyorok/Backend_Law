<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cases;
use Illuminate\Support\Facades\Auth;

class CaseController extends Controller
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

        // Check if the user is an admin
        if ($user->role === 'admin') {
            $casesQuery = Cases::query();
        } else {
            $casesQuery = Cases::where('user_id', $user->id);
        }

        if ($query) {
            $casesQuery->where('title', 'LIKE', '%' . $query . '%')
                    ->orWhere('description', 'LIKE', '%' . $query . '%'); 
        }

        $cases = $casesQuery->orderBy('created_at', 'desc')->latest()->paginate($per_page); 
        
        if ($cases->isEmpty()) {
            return response()->json(['message' => 'No cases found'], 404);
        }
        
        return response()->json($cases);
    }

    public function show($id)
    {
        $user = Auth::user();

        $case = Cases::find($id);
        if ($case) {
            if ($case->user_id !== $user->id && $user->role !== 'admin') {
                return response()->json(['message' => 'Cannot show case'], 403);
            } else if($user->role === 'admin' || $user->role === 'user') {
                return response()->json($case);
            }
        } else {
            return response()->json(['message' => 'Case not found'], 404);
        }
    }
    
    public function store(Request $request)
    {
        $user = Auth::user();
    
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'stakeholders' => 'required|string',
            'status' => 'required|string',
        ]);
    
        $stakeholdersArray = json_decode($data['stakeholders'], true);
    
        $case = new Cases();
        $case->title = $data['title'];
        $case->description = $data['description'];
        $case->stakeholders = $stakeholdersArray; 
        $case->status = $data['status'];
        $case->user_id = $user->id;

        if ($case->save()) {
            return response()->json(['data' => 'Case was added successfully', 'status' => 'success'], 200);
        } else {
            return response()->json(['error' => 'Case was not created.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $case = Cases::find($id);
        if ($case) {
            if ($case->user_id !== $user->id) {
                return response()->json(['message' => 'Cannot update case'], 403);
            }
            $data = $request->validate([
                'title' => 'required|string',
                'description' => 'required|string',
                'stakeholders' => 'required|string',
                'status' => 'required|string',
            ]);
            $stakeholdersArray = json_decode($data['stakeholders'], true);
            $case->title = $data['title'];
            $case->description = $data['description'];
            $case->stakeholders = $stakeholdersArray;
            $case->status = $data['status'];
            if ($case->save()) {
                return response()->json(['data' => 'Case was updated successfully', 'status' => 'success'], 200);
            } else {
                return response()->json(['error' => 'Case was not updated.'], 500);
            }
        } else {
            return response()->json(['message' => 'Case not found'], 404);
        }
    }
}
