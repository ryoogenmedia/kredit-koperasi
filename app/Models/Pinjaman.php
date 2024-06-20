<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function nasabah(){
        return $this->belongsTo(Nasabah::class,'nasabah_id','id')->withDefault();
    }
}
