<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Olimpista extends Model {
    protected $fillable = [
        'nombre_completo','ci','unidad_educativa','departamento','area_id','nivel_id','tutor'
    ];
    public function area(){ return $this->belongsTo(Area::class); }
    public function nivel(){ return $this->belongsTo(Nivel::class); }
}
