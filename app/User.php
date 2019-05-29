<?php

namespace App;

use App\Rol;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function rols()
    {
        return $this->belongsToMany(Rol::class)->withTimestamps();
    }

    public function authorizeRols($rols)
    {
        abort_unless($this->hasAnyRols($rols), 401);

        return true;
    }

    public function hasAnyRols($rols)
    {
        if (is_array($rols)) {
            foreach ($rols as $rol) {
                if ($this->hasRols($rol)) {
                    return true;
                }
            }
        }else {
            if ($this->hasRols($rol)) {
                return true;
            }
        }
        return false;
    }

    public function hasRol($rol)
    {
        if ($this->rols()->where('nombre', $rol)->first()) {
            return true;
        }
    }
}
