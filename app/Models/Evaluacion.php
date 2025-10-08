<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model {
    protected $fillable = ['olimpista_id','fase','nota','observacion','evaluador_id'];
    public function olimpista(){ return $this->belongsTo(Olimpista::class); }
    public function evaluador(){ return $this->belongsTo(Evaluador::class); }
}
