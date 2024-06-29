<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Angsuran extends Model
{
    use HasFactory;

    protected $table = "angsuran";

    protected $fillable = [
        'pinjaman_id',
        'nasabah_id',
        'date_installments',
        'installments_to',
        'remaining_installments',
        'remaining_loan',
        'proof',
        'confirmation_repayment',
    ];

    protected $casts = [
        'pinjaman_id' => 'integer',
        'nasabah_id' => 'integer',
        'date_installments' => 'datetime',
        'remaining_installments' => 'integer',
        'remaining_loan' => 'integer',
        'proof' => 'string',
        'confirmation_repayment' => 'boolean',
    ];

    public function pinjaman(){
        return $this->belongsTo(Pinjaman::class,'pinjaman_id','id')->withDefault();
    }

    public function nasabah(){
        return $this->belongsTo(Nasabah::class,'nasabah_id','id')->withDefault();
    }

    public function detail(){
        return $this->hasOne(DetailAngsuran::class,'angsuran_id','id')->withDefault();
    }
}
