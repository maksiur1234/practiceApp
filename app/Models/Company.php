<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Company extends Model implements Authenticatable
{
    use AuthenticatableTrait;
    use HasFactory;

//    protected $fillable = ['companyName', 'email', 'password', 'type_id'];
//
    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    protected $fillable = [
        'companyName',
        'email',
        'password',
        'provider',
        'provider_id',
        'provider_token',
        'type_id'
        // inne pola Twojego modelu Company
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Inne relacje, atrybuty lub metody Twojego modelu Company

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the remember token for the user.
     *
     * @return string|null
     */
    public function getRememberToken()
    {
        return $this->remember_token;
    }

    /**
     * Set the remember token for the user.
     *
     * @param  string|null  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'companyOwner', 'username');
    }
}
