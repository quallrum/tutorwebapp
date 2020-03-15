<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model{

    protected $fillable = ['title', 'name', 'monitor'];

    public function setMonitorAttribute($id){
        $this->attributes['monitor_id'] = $id;
    }

    public function monitor(){ return $this->belongsTo(User::class); }
    public function students(){ return $this->hasMany(Student::class); }
    public function subjects(){ return $this->belongsToMany(Subject::class); }
    public function tutors(){ return $this->belongsToMany(User::class, 'group_subject', 'group_id', 'tutor_id'); }
    // public function journals(){}
}
