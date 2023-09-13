<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Interfaces\UserServiceInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'prefixname',
        'firstname',
        'middlename',
        'lastname',
        'suffixname',
        'username',
        'email',
        'password',
        'photo',
        'type',
        'deleted_at',
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

    public function details()
    {
        return $this->hasMany(Details::class);
    }

    public function setPasswordAttribute($password)
    {
        $userService = app(UserServiceInterface::class);
        $this->attributes['password'] = $userService->hash($password);
    }

    public function getFullNameAttribute()
    {
        return ucfirst($this->lastname) . ' ' . $this->getMiddleinitialAttribute() . ' ' . ucfirst($this->firstname);
    }

    public function getMiddleinitialAttribute()
    {
        if (!empty($this->middlename)) {
            return ucfirst($this->middlename[0]) . '.';
        }
        return '';
    }

    public function getAvatarAttribute()
    {
        $avatarUrl = env('AVATAR_URL');
        if ($this->photo === '') {
            return "$avatarUrl/avatar/avatarNoImg3.jpg";
        }
        return "$avatarUrl/{$this->photo}";
    }
}
