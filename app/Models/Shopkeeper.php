<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Shopkeeper extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'ShopkeeperId';
    protected $fillable = ['UserName', 'Password'];
    
    protected $hidden = [
        'Password',
        'remember_token',
    ];

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->Password;
    }

    /**
     * Get the username field name.
     *
     * @return string
     */
    public function username()
    {
        return 'UserName';
    }
} 