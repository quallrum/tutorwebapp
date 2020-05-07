<?php

namespace App\Models\Journal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Journal extends Model{

	use SoftDeletes;

	protected $table = 'journal_records';
	protected $fillable = ['student_id', 'value'];
	protected $attributes = ['value' => 0];

	public function editable(){
		return (new \DateTime)->format('Y-m-d') === (new \DateTime($this->attributes['created_at']))->format('Y-m-d');
	}

	public function getDateAttribute(){
		if(isset($this->attributes['created_at'])) return (new \DateTime($this->attributes['created_at']))->format('d.m');
		return '';
	}

	public function getValueAttribute(){
		if($this->attributes['value'] === null) return 'н';
		else return $this->attributes['value'] == 0 ? '' : $this->attributes['value'];
	}
	
	public function setValueAttribute($value){
		if($value == 'н') return $this->attributes['value'] = null;
		else return $value == '' ? $this->attributes['value'] = 0 : $this->attributes['value'] = $value;
	}

	public function column(){ return $this->belongsTo(JournalColumn::class); }
	public function student(){ return $this->belongsTo(Student::class); }
}
