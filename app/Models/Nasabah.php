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
        'number_identity',
        'name',
        'address',
        'job',
        'phone',
        'age',
        'email',
    ];

    protected $casts = [
        'number_identitiy' => 'integer',
        'name' => 'string',
        'address' => 'string',
        'job' => 'string',
        'phone' => 'integer',
        'age' => 'integer',
        'email' => 'string',
    ];

    public function pinjaman(){
        return $this->hasMany(Pinjaman::class,'nasabah_id','id');
    }

    public function angsuran(){
        return $this->hasMany(Angsuran::class,'nasabah_id','id');
    }
}
