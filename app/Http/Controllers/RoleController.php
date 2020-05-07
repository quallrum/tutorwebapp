<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Tutor;
use App\Models\Role;

class RoleController extends Controller{
	
	public function index(){
		$this->authorize('role.list');

		$users = User::where('id', '>', 1)->orderBy('email', 'asc')->get();
		$roles = Role::all();

		return view('role.index', [
			'users'	=> $users,
			'roles'	=> $roles,
		]);
	}

	public function role(Request $request){
		$data = $request->validate([
			'user'	=> ['required', 'integer'],
			'role'	=> ['required', 'integer'],
		]);

		$user = User::find($data['user']);
		$role = Role::find($data['role']);

		$this->authorize('role.change', [$user, $role]);
		if($user->id == 1) return response('', 403);
		if($user->role->id == $role->id) return response()->json(['message' => 'Not modified.'], 200);

		$result = true;

		if($user->role->name == 'tutor'){
			$tutor = Tutor::find($user->id);
			if(!$tutor){
				Log::error('User '.Auth::user()->id.' tried to disassociate role \'tutor\' from user '.$user->id.' but tutor record not found');
				return response('', 500);
			}
			if(!$tutor->delete()){
				Log::error('User '.Auth::user()->id.' tried to disassociate role \'tutor\' from user '.$user->id.' but failed to delete tutor record');
				return response()->json(['message' => 'Failed!'], 500);
			}
		}

		$user->role()->associate($role);
		$save = $user->save();
		
		if($role->name == 'tutor'){
			$tutor = new Tutor;
			$tutor->user()->associate($user);
			$result = $tutor->save();
		}

		if($save and $result) return response()->json(['message' => 'Updated!'], 200);
		else{
			if(!$save)		Log::error('User '.Auth::user()->id.' failed to associate role '.$role->id.' to user '.$user->id.' due to unexpected error');
			if(!$result)	Log::error('User '.Auth::user()->id.' failed to perform additional operations after associating role '.$role->id.' to user '.$user->id.' due to unexpected error');
			return response()->json(['message' => 'Failed'], 500);
		}
	}

}
