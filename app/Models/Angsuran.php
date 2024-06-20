<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Angsuran extends Model
{
 use HasFactory;

 protected $table = "angsuran";
 protected $fillable = [
    'id_pinjaman',
    'tgl_angsuran',
    'angsur_ke',
    'sisa_angsur',
    'sisa_pinjam',
    'bukti_angsur',
 ];

}
