<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolesPolicy{
	use HandlesAuthorization;

	public function before($user){
        if($user->role->name == 'admin') return true;
	}

	public function list($user){
		return false;
	}

	public function change($user, $u, $r){
		return false;
	}

}
