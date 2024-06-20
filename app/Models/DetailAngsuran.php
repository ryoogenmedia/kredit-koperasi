<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailAngsuran extends Model
{
use HasFactory;

protected $table = "detail_angsuran";
protected $fillable = [
    'id_detail_pinjaman',
    'jumlah_angsur',
    'keterangan_angsur',
 ];

}
