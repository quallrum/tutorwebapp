<?php

namespace App\Models\Mark;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Student;

class Mark extends Model{

	use SoftDeletes;

	protected $table = 'mark_records';
	protected $fillable = ['student_id', 'value'];
	protected $attributes = ['value' => 0];

	public function getDateAttribute(){
		if(isset($this->attributes['created_at'])) return (new \DateTime($this->attributes['created_at']))->format('d.m');
		return '';
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
