<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
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
        'name',
        'email',
        'password',
        'username',
        'provider',
        'provider_id',
        'provider_token'
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

    public static function generateUserName($username)
    {
        if($username == null)
        {
            $username = Str::lower(Str::random(8));
        }

        if(User::where('username', $username)->exists())
        {
            $newUsername = $username.Str::lower(Str::random(3));
            $username = self::generateUserName($newUsername);
        }

        return $username;
    }

    public function companies()
    {
<<<<<<< HEAD
        return $this->hasMany(Company::class, 'user_id', 'username');
=======
        return $this->hasMany(Company::class, 'companyOwner', 'username');
>>>>>>> origin/main
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function visits()
    {
        return $this->hasMany(Event::class);
    }
}
