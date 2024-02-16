<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cases;

class CaseController extends Controller
{
    public function index()
    {
        $cases = Cases::all();
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
}
