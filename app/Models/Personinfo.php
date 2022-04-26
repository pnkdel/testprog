<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personinfo extends Model
{
    //use HasFactory;
    protected $table = 'personinfos';
    protected $primaryKey = 'person_id';

    static  function getPersonData($person_id) {
        $individual = self::where('person_id', '=',$person_id)->get();
        return $individual;
    }
}
