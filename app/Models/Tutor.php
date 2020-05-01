<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tutor extends Model{
	
	protected $fillable = ['firstname', 'lastname', 'fathername'];
	public $timestamps = false;
	protected $primaryKey = 'user_id';

	public function getfullnameAttribute(){
		return $this->attributes['lastname'].' '.
				$this->attributes['firstname'].' '.
				$this->attributes['fathername'];
	}

	public function getshortFullnameAttribute(){
		return $this->attributes['lastname'].' '.
				mb_substr($this->attributes['firstname'], 0, 1).'. '.
				mb_substr($this->attributes['fathername'], 0, 1).'.';
	}

	public function checkFullname(){
		return 	$this->attributes['firstname'] != '' and
				$this->attributes['lastname'] != '' and
				$this->attributes['fathername'] != '';
	}

	public function user(){ return $this->belongsTo(User::class); }

}
