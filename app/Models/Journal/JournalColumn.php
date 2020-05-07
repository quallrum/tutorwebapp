<?php

namespace App\Models\Journal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use App\Models\Subject;
use App\Models\Group;

class JournalColumn extends Model{

	use SoftDeletes;

	public static function canAdd($group, $subject){
		$group = $group instanceof Group ? $group->id : $group;
		$subject = $subject instanceof Subject ? $subject->id : $subject;

		$r = static::where('group_id', $group)
			->where('subject_id', $subject)
			->selectRaw('max(created_at) as created_at')
			->first();

		if(!$r){
			Log::error('Can\'t check if it\'s allowed to add new JourlanColumn - failed to get last date from DB');
			return false;
		}
		return (new \DateTime($r->created_at))->format('Y-m-d') == (new \DateTime)->format('Y-m-d');
	}

	public function editable(){
		return (new \DateTime)->format('Y-m-d') === (new \DateTime($this->attributes['created_at']))->format('Y-m-d');
	}

	public function getDateAttribute(){
		if(isset($this->attributes['created_at'])) return (new \DateTime($this->attributes['created_at']))->format('d.m');
		return '';
	}

	public function subject(){ return $this->belongsTo(Subject::class); }
	public function group(){ return $this->belongsTo(Group::class); }
	public function records(){ return $this->hasMany(Journal::class, 'column_id'); }
}
