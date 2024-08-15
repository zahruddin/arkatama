<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penumpangs extends Model
{
    use HasFactory;
    protected $fillable = [
        'travel_id',
        'kode_booking',
        'nama',
        'jenis_kelamin',
        'kota',
        'usia',
        'tahun_lahir',
    ];
    public function travel()
    {
        return $this->belongsTo(Travels::class);
    }
}
