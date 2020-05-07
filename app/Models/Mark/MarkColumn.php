<?php

namespace App\Models\Mark;

use Illuminate\Database\Eloquent\Model;
use App\Models\Subject;
use App\Models\Group;

class MarkColumn extends Model{
	public function subject(){ return $this->belongsTo(Subject::class); }
	public function group(){ return $this->belongsTo(Group::class); }
}
