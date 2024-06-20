<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailAngsuran extends Model
{
    use HasFactory;

    protected $table = "detail_angsuran";

    protected $fillable = [
        'angsuran_id',
        'detail_pinjaman_id',
        'amount_installments',
        'note',
    ];

    protected $casts = [
        'angsuran_id' => 'integer',
        'detail_pinjaman_id' => 'integer',
        'amount_installments' => 'integer',
        'note' => 'staring',
    ];

    public function detailPinjaman(){
        return $this->belongsTo(DetailPinjaman::class,'detail_pinjaman_id','id')->withDefault();
    }

    public function angsuran(){
        return $this->belongsTo(Angsuran::class,'angsuran_id','id')->withDefault();
    }
}
