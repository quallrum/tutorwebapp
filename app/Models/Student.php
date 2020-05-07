<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model{

    protected $fillable = ['firstname', 'lastname', 'fathername'];

    public function getShortnameAttribute(){
        return $this->attributes['lastname'].' '.
                $this->attributes['firstname'];
    }

    public function getFullnameAttribute(){
        return $this->attributes['lastname'].' '.
                $this->attributes['firstname'].' '.
                $this->attributes['fathername'];
    }

    public function group(){ return $this->belongsTo(Group::class); }
    // public function journals(){}
}
