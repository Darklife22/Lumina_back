<?php

namespace App\Http\Controllers;

use App\Models\Evaluador;
use Illuminate\Http\Request;

class EvaluatorsController extends Controller
{
    public function index()
    {
        return response()->json(['data' => Evaluador::with('area')->orderBy('id','desc')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'=>'required|string',
            'area_id'=>'required|integer|exists:areas,id',
            'email'=>'nullable|email',
            'telefono'=>'nullable|string'
        ]);
        $ev = Evaluador::create($data);
        return response()->json($ev, 201);
    }

    public function destroy($id)
    {
        $e = Evaluador::findOrFail($id);
        $e->delete();
        return response()->json(['message'=>'ok']);
    }
}
