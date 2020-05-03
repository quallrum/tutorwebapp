<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model{

    // Role -> id map
    public const admin      = 1;
    public const user       = 2;
    public const tutor      = 3;
    public const monitor    = 4;
    public const group      = 5;
    
    public function users(){ return $this->hasMany(User::class); }
}
