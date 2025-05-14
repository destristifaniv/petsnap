<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    protected $fillable = ['diagnosa_id', 'nama_obat', 'dosis', 'catatan'];

    public function diagnosa()
    {
        return $this->belongsTo(Diagnosa::class);
    }
}
