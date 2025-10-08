<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\Olimpista;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EvaluationsController extends Controller
{
    public function list(Request $request)
    {
        $request->validate([
            'fase'     => 'required|in:clasificacion,final',
            'area_id'  => 'required|integer',
            'nivel_id' => 'required|integer',
        ]);

        $rows = Evaluacion::with('olimpista')
            ->where('fase', $request->fase)
            ->whereHas('olimpista', function ($q) use ($request) {
                $q->where('area_id', $request->area_id)
                  ->where('nivel_id', $request->nivel_id);
            })
            ->get();

        // Si no existen evaluaciones para esos olimpistas+fase, creamos vacíos (opcional)
        if ($rows->isEmpty()) {
            $olimpistas = Olimpista::where('area_id', $request->area_id)
                ->where('nivel_id', $request->nivel_id)
                ->get();

            foreach ($olimpistas as $o) {
                Evaluacion::firstOrCreate([
                    'olimpista_id' => $o->id,
                    'fase'         => $request->fase,
                ]);
            }

            $rows = Evaluacion::with('olimpista')
                ->where('fase', $request->fase)
                ->whereHas('olimpista', fn ($q) =>
                    $q->where('area_id', $request->area_id)
                      ->where('nivel_id', $request->nivel_id)
                )
                ->get();
        }

        return response()->json(['data' => $rows]);
    }

    public function bulk(Request $request)
    {
        $items = $request->validate([
            'items'                => 'required|array',
            'items.*.olimpista_id' => 'required|integer|exists:olimpistas,id',
            'items.*.fase'         => 'required|in:clasificacion,final',
            'items.*.nota'         => 'nullable|numeric|min:0|max:100',
            'items.*.observacion'  => 'nullable|string',
        ])['items'];

        DB::transaction(function () use ($items) {
            foreach ($items as $it) {
                Evaluacion::updateOrCreate(
                    ['olimpista_id' => $it['olimpista_id'], 'fase' => $it['fase']],
                    [
                        'nota'        => $it['nota'] ?? null,
                        'observacion' => $it['observacion'] ?? null,
                    ]
                );
            }
        });

        AuditLog::create([
            'user_id' => Auth::id(),
            'accion'  => 'BULK_UPDATE',
            'detalle' => 'Actualización masiva de notas',
        ]);

        return response()->json(['message' => 'ok']);
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'area_id'  => 'required|integer',
            'nivel_id' => 'required|integer',
            'orden'    => 'required|array',
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'accion'  => 'REORDER',
            'detalle' => 'Reordenamiento de olimpistas: ' . json_encode($request->orden),
        ]);

        return response()->json(['message' => 'ok']);
    }

    public function closePhase(Request $request)
    {
        $data = $request->validate([
            'fase'     => 'required|in:clasificacion,final',
            'area_id'  => 'required|integer',
            'nivel_id' => 'required|integer',
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'accion'  => 'CLOSE_PHASE',
            'detalle' => json_encode($data),
        ]);

        return response()->json(['message' => 'ok']);
    }
}
