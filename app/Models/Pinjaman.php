<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{

use HasFactory;

protected $table = "pinjaman";
protected $fillable = [
'id_nasabah',
'jumlah_pinjaman',
'bunga',
'tgl_pinjaman',
'jumlah_angsur',
'total_angsur',
];

}
