<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubjectPolicy{
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

	public function edit($user){
		return false;
	}

	public function delete($user){
		return false;
	}

}
