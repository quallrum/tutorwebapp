<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectType extends Model{

	public static $hasMarks = [2, 3, 4];

	public static function hasMarks($type){
		$type = $type instanceof SubjectType ? $type->id : $type;
		return \in_array($type, static::$hasMarks);
	}

	public function subjects(){ return $this->hasMany(Subject::class, 'type_id'); }
}
