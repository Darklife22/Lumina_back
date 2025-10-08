<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function certificados(Request $request)
    {
        $request->validate(['area_id'=>'required|integer','nivel_id'=>'required|integer']);
        // Retorna una lista ejemplo; en real, genera Excel/CSV
        return response()->json(['message'=>'certificados listo']);
    }

    public function ceremonia(Request $request)
    {
        $request->validate(['area_id'=>'required|integer','nivel_id'=>'required|integer']);
        return response()->json(['message'=>'ceremonia lista']);
    }

    public function publicacion(Request $request)
    {
        $request->validate(['area_id'=>'required|integer','nivel_id'=>'required|integer']);
        return response()->json(['message'=>'publicación lista']);
    }
}
