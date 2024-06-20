<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nasabah extends Model
{

use HasFactory;

protected $table = 'nasabah';
protected $fillable = [
'user_id',
'no_ktp',
'nama_nasabah',
'alamat',
'umur',
'no_telp',
];

}
