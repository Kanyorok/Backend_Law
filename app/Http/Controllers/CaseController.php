<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cases;
use Illuminate\Support\Facades\Auth;

class CaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['show']);
    }

    public function index()
    {
        $user = Auth::user(); 
        
        // Check if user is authenticated
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $cases = Cases::where('user_id', $user->id)->get(); 
        
        if ($cases->isEmpty()) {
            return response()->json(['message' => 'No cases created by this user'], 404);
        }
        
        return response()->json($cases);
    }

    public function show($id)
    {
        $case = Cases::find($id);
        if ($case) {
            return response()->json($case);
        } else {
            return response()->json(['message' => 'Post not found'], 404);
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
}
