<?php

namespace App;

use Illuminate\Notifications\Notifiable;
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
     * Route notifications for the slack channel.
     *
     * @return string
     */
    public function routeNotificationForSlack()
    {
        
        return 'https://hooks.slack.com/services/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';
        
    }

    /**
     * Route notifications for the mail channel.
     *
     * @return string
     */
    // public function routeNotificationForMail()
    // {
    //     return $this->email;
    // }
}
