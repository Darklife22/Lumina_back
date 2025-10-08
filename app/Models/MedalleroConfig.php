<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MedalleroConfig extends Model {
    protected $fillable = ['area_id','oros','platas','bronces','menciones'];
}
