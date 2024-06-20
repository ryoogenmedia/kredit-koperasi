<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPinjaman extends Model
{
    use HasFactory;

    protected $table = "detail_pinjaman";

    protected $fillable = [
        'pinjaman_id',
        'date_submission_loan',
        'date_acc_loan',
        'remaining_loan',
        'note',
    ];

    public function pinjaman(){
        return $this->belongsTo(Pinjaman::class,'pinjaman_id','id')->withDefault();
    }
}
