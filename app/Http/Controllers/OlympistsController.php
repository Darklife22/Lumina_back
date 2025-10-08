<?php

namespace App\Http\Controllers;

use App\Models\Olimpista;
use Illuminate\Http\Request;
use League\Csv\Reader;

class OlympistsController extends Controller
{
    public function index(Request $request)
    {
        // filtros simples opcionales
        $q = Olimpista::with(['area','nivel'])->orderBy('id','desc');
        return response()->json(['data' => $q->paginate(50)]);
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:csv,txt']);
        $csv = Reader::createFromPath($request->file('file')->getRealPath(), 'r');
        $csv->setHeaderOffset(0);

        $inserted = 0; $updated = 0;
        foreach ($csv as $row) {
            // columnas esperadas: nombre_completo, ci, unidad_educativa, departamento, area_id, nivel_id, tutor
            $payload = [
                'nombre_completo'  => $row['nombre_completo'] ?? '',
                'ci'               => $row['ci'] ?? null,
                'unidad_educativa' => $row['unidad_educativa'] ?? '',
                'departamento'     => $row['departamento'] ?? '',
                'area_id'          => (int)($row['area_id'] ?? 0),
                'nivel_id'         => (int)($row['nivel_id'] ?? 0),
                'tutor'            => $row['tutor'] ?? null,
            ];
            $existing = null;
            if (!empty($payload['ci'])) {
                $existing = Olimpista::where('ci', $payload['ci'])->first();
            }
            if ($existing) { $existing->update($payload); $updated++; }
            else { Olimpista::create($payload); $inserted++; }
        }
        return response()->json(['inserted'=>$inserted,'updated'=>$updated]);
    }
}
