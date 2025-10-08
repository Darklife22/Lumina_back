<?php

namespace App\Http\Controllers;

use App\Models\MedalleroConfig;
use Illuminate\Http\Request;

class MedalleroController extends Controller
{
    public function get(Request $request)
    {
        $request->validate(['area_id'=>'required|integer']);
        $cfg = MedalleroConfig::firstOrCreate(['area_id'=>$request->area_id]);
        return response()->json($cfg);
    }

    public function set(Request $request)
    {
        $data = $request->validate([
            'area_id'=>'required|integer',
            'oros'=>'required|integer|min:0',
            'platas'=>'required|integer|min:0',
            'bronces'=>'required|integer|min:0',
            'menciones'=>'required|integer|min:0',
        ]);
        $cfg = MedalleroConfig::updateOrCreate(['area_id'=>$data['area_id']], $data);
        return response()->json($cfg);
    }
}
