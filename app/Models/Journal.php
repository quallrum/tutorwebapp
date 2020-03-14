<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Group;
// use Subject;

class Journal extends Model{

	public function editable(){
		return (new \DateTime)->format('Y-m-d') === (new \DateTime($this->attributes['created_at']))->format('Y-m-d');
	}

	public function getDateAttribute(){
		return (new \DateTime($this->attributes['created_at']))->format('d.m');
	}
	public function getValueAttribute(){
		if(is_null($this->attributes['value'])) return 'н';
		elseif($this->attributes['value'])      return $this->attributes['value'];
		else                                    return '';
	}

	public static function table(Group $group, Subject $subject){
		$students = $group->students->pluck('id')->all();
		$journal  = [];

		foreach ($students as $id) {
			$journal[$id] = static::where('subject_id', $subject->id)
				->where('student_id', $id)
				->orderBy('created_at')
				->get();
		}

		return $journal;
	}

	public function student(){ return $this->belongsTo(Student::class); }
	public function subject(){ return $this->belongsTo(Subject::class); }
}
