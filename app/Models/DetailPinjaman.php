<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPinjaman extends Model
{
use HasFactory;
protected $table = "detail_pinjaman";
protected $fillable = [
   'id_nasabah',
   'tgl_pengajuan_pinjaman',
   'tgl_acc_pinjaman',
   'tgl_pelunasan',
   'sisa_pinjaman',
   'bunga_pinjaman',
   'keterangan_pinjaman',
];

}
