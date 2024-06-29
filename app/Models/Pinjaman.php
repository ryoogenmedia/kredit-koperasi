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
        'status_akad',
        'confirmation_nasabah',
        'installments_type',
    ];

    protected $casts = [
        'nasabah_id' => 'integer',
        'amount' => 'integer',
        'date' => 'datetime',
        'interest' => 'integer',
        'installments' => 'integer',
        'amount_installments' => 'integer',
        'status_akad' => 'string',
        'installments_type' => 'string',
        'confirmation_nasabah' => 'boolean',
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
