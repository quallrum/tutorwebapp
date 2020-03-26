<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Group;
// use Subject;

class Journal extends Model{

	protected $fillable = ['student_id', 'subject_id', 'value'];

	public static function lastDate($student, $subject){
		$student = $student instanceof Student ? $student->id : $student;
		$subject = $subject instanceof Subject ? $subject->id : $subject;

		$r = static::where('student_id', $student)
			->where('subject_id', $subject)
			// ->where('subject_id', 1000)
			->orderBy('created_at', 'desc')
			->take(1)
			->get('created_at')
			->first();
		if($r) return (new \DateTime($r->created_at))->format('Y-m-d');
		else return (new \DateTime)->format('Y-m-d');
	}

	public function editable(){
		return (new \DateTime)->format('Y-m-d') === (new \DateTime($this->attributes['created_at']))->format('Y-m-d');
	}

	public function getDateAttribute(){
		return (new \DateTime($this->attributes['created_at']))->format('d.m');
	}
	public function getValueAttribute(){
		if($this->attributes['value'])	return '';
		else							return 'н';
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
