<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model{

	protected $fillable = ['title', 'type'];

	protected function joined($query, $table){
		if($joins = $query->getQuery()->joins){
			foreach ($joins as $join) {
				if($join->table == $table) return true;
			}
		}
		return false;
	}

	public function scopeofGroup($query, $group){
		$group = $group instanceof Group ? $group->id : $group;
		
		if($this->joined($query, 'group_subject')) return $query->where('group_subject.group_id', $group);
		else return $query->join('group_subject', 'subjects.id', '=', 'group_subject.subject_id')
			->where('group_subject.group_id', $group);
			// ->groupBy('group_subject.group_id');
			// ->get('groups.*');
	}


	public function scopeofTutor($query, $user){
		$user = $user instanceof User ? $user->id : $user;
		
		if($this->joined($query, 'group_subject')) return $query->where('group_subject.tutor_id', $user);
		return $query->join('group_subject', 'subjects.id', '=', 'group_subject.subject_id')
			->where('group_subject.tutor_id', $user);
			// ->groupBy('group_subject.group_id');
			// ->get('groups.*');
	}    
	
	public function hasTutor($tutor){
		$tutor = $tutor instanceof Tutor ? $tutor->user_id : $tutor;
		return (bool) $this->tutors()->where('tutor_id', $tutor)->count();
	}

	public function otherTutors(){
		return Tutor::whereNotIn('user_id', $this->tutors()->pluck('user_id'))->get();
	}

	public function type(){ return $this->belongsTo(SubjectType::class, 'type_id'); }
	public function groups(){ return $this->belongsToMany(Group::class); }
	public function tutors(){ return $this->belongsToMany(Tutor::class, 'tutor_subject', 'subject_id', 'tutor_id'); }
}
