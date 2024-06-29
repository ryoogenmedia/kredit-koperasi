<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        'status_verification',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'number_identitiy' => 'integer',
        'name' => 'string',
        'address' => 'string',
        'job' => 'string',
        'phone' => 'integer',
        'age' => 'integer',
        'email' => 'string',
        'status_verification' => 'boolean',
    ];

    public function pinjaman(){
        return $this->hasMany(Pinjaman::class,'nasabah_id','id');
    }

    public function angsuran(){
        return $this->hasMany(Angsuran::class,'nasabah_id','id');
    }

    // AUTOMATICLY DELETING RELATIONSHIP
    public function delete()
    {
        DB::transaction(function () {
            $this->angsuran()->each(function ($angsuran) {
                $angsuran->detail->delete();
                $angsuran->delete();
            });

            $this->pinjaman()->each(function ($pinjaman) {
                $pinjaman->detail->delete();
                $pinjaman->delete();
            });

            parent::delete();
        });
    }
}
