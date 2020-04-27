<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy{
    use HandlesAuthorization;

    public function before($user){
        if($user->role->name == 'admin') return true;
	}
	
	public function list($user){
		return false;
	}

	public function create($user){
		return false;
	}

    public function edit($user, Group $group){
        return $user->role->name == 'monitor' and $user->id == $group->monitor->id;
	}
	
	public function monitor($user){
		return false;
	}

}
