<?php

namespace App\Models;

use Firebase\JWT\JWT;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'force_logout',
        'username',
        'status',
        'avatar',
        'roles',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // GET AVATAR URL
    public function avatarUrl()
    {
        return $this->avatar
            ? url('storage/' . $this->avatar)
            : 'https://gravatar.com/avatar/' . md5(strtolower(trim($this->email))) . '?s=1024';
    }

    public function getJWTToken(){
        $payload = [
            'iss' => "jwt-ryoogen-media",
            'sub' => $this->id,
            'iat' => time(),
            'exp' => time() + config('jwt.ttl') * 60
        ];

        return JWT::encode($payload, config('jwt.secret'), 'HS256');
    }

    public function nasabah(){
        return $this->hasOne(Nasabah::class, 'user_id','id')->withDefault();
    }

    // AUTOMATICLY DELETING RELATIONSHIP
    public function delete(){
        $this->nasabah->delete();

        parent::delete();
    }
}
