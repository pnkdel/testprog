<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appuser extends Model
{
    //use HasFactory;
    protected $table = 'appusers';
    protected $primaryKey = 'user_id';



    static  function verifyUser($email, $pswd) {

        $user = self::where('email', '=',$email)
        ->where('pswd', '=',  md5($pswd))
        ->get();

        return $user;

    }
}
