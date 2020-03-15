<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;

class Group extends Model{

	protected $fillable = ['title', 'name', 'monitor'];

	public function setMonitor($monitor){
		if($monitor instanceof User) $monitor = $monitor->id;
		$monitor = is_numeric($monitor) ? (int) $monitor : null;
		$role_user = Role::where('name', 'user')->first();
		$role_monitor = Role::where('name', 'monitor')->first();
		// dd(DB::table('users')->where('id', null)->update(['role_id' => 2]));

		try{
			DB::transaction(function() use($monitor, $role_user, $role_monitor) {
				DB::table('users')->where('id', $this->attributes['monitor_id'])->update(['role_id' => $role_user->id]);
				DB::table('users')->where('id', $monitor)->update(['role_id' => $role_monitor->id]);
				DB::table('groups')->where('id', $this->attributes['id'])->update(['monitor_id' => $monitor]);
				$this->attributes['monitor_id'] = $monitor;
			}, 2);
			return true;
		} catch(\Exception $e){
			return false;
		}
	}

	public function setMonitorAttribute($id){
		$this->attributes['monitor_id'] = is_numeric($id) ? (int) $id : null;
	}

	public function monitor(){ return $this->belongsTo(User::class); }
	public function students(){ return $this->hasMany(Student::class); }
	public function subjects(){ return $this->belongsToMany(Subject::class); }
	public function tutors(){ return $this->belongsToMany(User::class, 'group_subject', 'group_id', 'tutor_id'); }
	// public function journals(){}
}
