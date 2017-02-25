<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;

class User extends User
{
    //
    use HasApiTokens,Notifiable;
}
