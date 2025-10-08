<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Evaluador extends Model {
    protected $fillable = ['nombre','email','telefono','area_id'];
    public function area(){ return $this->belongsTo(Area::class); }
}
