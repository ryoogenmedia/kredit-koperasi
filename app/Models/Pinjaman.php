<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pinjaman extends Model
{

use HasFactory;

    protected $table = "pinjaman";

    protected $fillable = [
        'nasabah_id',
        'amount',
        'interest',
        'date',
        'installments',
        'amount_installments',
    ];

    protected $casts = [
        'nasabah_id' => 'integer',
        'amount' => 'integer',
        'date' => 'datetime',
        'installments' => 'integer',
        'amount_installments' => 'integer',
    ];

    public function detail(){
        return $this->hasOne(DetailPinjaman::class, 'pinjaman_id', 'id')->withDefault();
    }

    public function nasabah(){
        return $this->belongsTo(Nasabah::class,'nasabah_id','id')->withDefault();
    }

    public function angsuran(){
        return $this->hasMany(Angsuran::class, 'pinjaman_id','id');
    }

    public function latestAngsuran(){
        return $this->hasOne(Angsuran::class, 'pinjaman_id', 'id')->latestOfMany();
    }
}
