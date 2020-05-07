<?php

namespace App\Models\Mark;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Subject;
use App\Models\Group;

class MarkColumn extends Model{

	use SoftDeletes;

	public function subject(){ return $this->belongsTo(Subject::class); }
	public function group(){ return $this->belongsTo(Group::class); }
	public function records(){ return $this->hasMany(Mark::class); }
}
