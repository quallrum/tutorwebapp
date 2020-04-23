<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;

class Group extends Model{

	protected $fillable = ['title', 'name'];

	public static function ofTutor($user){
		$user = $user instanceof User ? $user->id : $user;
		return static::join('group_subject', 'groups.id', '=', 'group_subject.group_id')
			->where('group_subject.tutor_id', $user)
			->groupBy('group_subject.group_id')
			->get('groups.*');
	}

	public function getStudent($student){
		$student = $student instanceof Student ? $student->id : $student;
		$student = Student::where('id', $student)->where('group_id', $this->id)->first();
		return $student !== null ? $student: false;
	}

	public function hasStudent($student){
		$student = $student instanceof Student ? $student->id : $student;
		return (bool) static::join('students', 'students.group_id', '=', 'groups.id')
			->where('groups.id', $this->id)
			->where('students.id', $student)
			->get()
			->first();
	}

	public function monitor(){ return $this->belongsTo(User::class); }
	public function user(){ return $this->belongsTo(User::class); }
	// stands for 'monitor-student' - ref to the record in students table 
	// because 'monitor' been already taken by monitor account
	public function ms(){ return $this->belongsTo(Student::class, 'ms_id'); }
	public function students(){ return $this->hasMany(Student::class)->orderBy('lastname')->orderBy('firstname')->orderBy('fathername'); }
	public function subjects(){ return $this->belongsToMany(Subject::class); }
	public function tutors(){ return $this->belongsToMany(User::class, 'group_subject', 'group_id', 'tutor_id'); }
	// public function journals(){}
}
