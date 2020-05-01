<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

	public function hasSubject($subject){
		$subject = $subject instanceof Subject ? $subject->id : $subject;
		return (bool) DB::table('group_subject')
			->where('group_id', $this->attributes['id'])
			->where('subject_id', $subject)
			->count();
	}

	public function addSubject($subject, $tutor){
		$subject = $subject instanceof Subject ? $subject->id : $subject;
		return (bool) DB::table('group_subject')->insert([
			'group_id'		=> $this->attributes['id'],
			'subject_id'	=> $subject,
			'tutor_id'		=> $tutor,
		]);
	}

	public function updateSubject($subject, $tutor){
		$subject = $subject instanceof Subject ? $subject->id : $subject;
		return (bool) DB::table('group_subject')
			->where('group_id', $this->attributes['id'])
			->where('subject_id', $subject)
			->where('tutor_id', $tutor)
			->update([
				'tutor_id'	=> $tutor,
			]);
	}

	public function deleteSubject($subject){
		$subject = $subject instanceof Subject ? $subject->id : $subject;
		return (bool) DB::table('group_subject')
			->where('group_id', $this->attributes['id'])
			->where('subject_id', $subject)
			->delete();
	}

	// Special string compare method to support RU and UA special symbols
	// Case insensitive
	protected function strLocaleCompare($a, $b){
		$a = mb_strtolower($a);
		$b = mb_strtolower($b);

		$lenA = mb_strlen($a);
		$lenB = mb_strlen($b);

		// Dict of special chars that follow regular chars in alphabet
		$special = [
			'ё' => 'е', // 1105 - 1077
			'є'	=> 'е', // 1108 - 1077
			'і' => 'и', // 1110 - 1080
			'ї' => 'и', // 1111 - 1080
		];

		$posA = [];
		$posB = [];

		// Find all entries of special chars
		foreach ($special as $char => $prev) {
			$pos = mb_strpos($a, $char);
			if($pos !== false) $posA[] = $pos;

			$pos = mb_strpos($b, $char);
			if($pos !== false) $posB[] = $pos;
		}

		// If no special symbols found, use default string compare function
		if(!$posA and !$posB) return strcmp($a, $b);

		// Now use special comparison
		// At first, trying to compare substrings before first special symbol

		// Get minimal substring length
		if($posA and $posB) $len = min(min($posA), min($posB));
		else $len = $posA ? min($posA) : min($posB);

		// If one of string less then length to compare, get the minimal length
		$len = min($lenA, $lenB, $len);
		
		// If special symbol is not first, try to compare the substrings
		if($len > 0){
			$subA = mb_substr($a, 0, $len);
			$subB = mb_substr($b, 0, $len);

			$cmp = strcmp($subA, $subB);
			if($cmp !== 0) return $cmp;
		}

		// There is no way but char-by-char string compare
		// Start from first special symbol found;
		$end = min($lenA, $lenB) - 1;
		for($i = $len; $i < $end; $i++){
			// Using mb_substr because array-like access to current char ($a[$i]) doesn't work correctly
			$charA = mb_substr($a, $i, 1);
			$charB = mb_substr($b, $i, 1);

			if($charA == $charB) continue;

			// Check if current chars from strings A and B are special chars
			$specA = array_key_exists($charA, $special);
			$specB = array_key_exists($charB, $special);

			// Both A and B chars are specials
			if($specA and $specB){
				// If previous letters for A and B are the same, compare A and B char codes
				if($special[$charA] == $special[$charB]) return $charA > $charB ? 1 : -1;
				// Else compare previous chars of A and B
				else return $special[$charA] > $special[$charB] ? 1 : -1;
			}
			// Compare special char with regular char
			else{
				// Check if special char should be placed after regular or not
				if($specA)	return $charB <= $special[$charA] ? 1 : -1;
				else 		return $charA <= $special[$charB] ? -1 : 1;
			}
		}

		// If code get here, strings are the same until the end of the shorter string
		// So, longer string should follow the shorter
		return $lenA > $lenB ? 1 : -1;
	}

	public function subjectTutorMap(){
		return DB::table('group_subject')
			->where('group_id', $this->id)
			->pluck('tutor_id', 'subject_id')
			->all();
	}

	public function getStudentsAttribute(){
		$students = $this->students()->get()->all();

		usort($students, function($a, $b){
			return $this->strLocaleCompare($a->fullname, $b->fullname);
		});

		return collect($students);
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
