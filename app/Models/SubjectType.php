<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectType extends Model{
	public function subjects(){ return $this->hasMany(Subject::class, 'type_id'); }
}
