<?php

namespace App\Models\Mark;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Student;

class Mark extends Model{

	use SoftDeletes;

	protected $table = 'mark_records';
	protected $fillable = ['student_id', 'value'];

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
		return $this->attributes['value'] === null ? null : $this->attributes['value'];
	}
	
	public function setValueAttribute($value){
		return $value == '' ? $this->attributes['value'] = null : $this->attributes['value'] = $value;
	}

	public function column(){ return $this->belongsTo(MarkColumn::class); }
	public function student(){ return $this->belongsTo(Student::class); }
}
