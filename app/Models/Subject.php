<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model{
    public function groups(){ return $this->belongsToMany(Group::class); }
    public function tutors(){ return $this->belongsToMany(User::class, 'tutor_subject', 'subject_id', 'tutor_id'); }
}
