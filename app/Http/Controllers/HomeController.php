<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\User\EditEmailRequest;
use App\Http\Requests\User\EditPasswordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Tutor;
use App\Models\Group;

class HomeController extends Controller{

    public function index(){
        $user = Auth::user();
        $role = $user->role;

        if($role->name == 'monitor')    $group = Group::where('monitor_id', $user->id)->first();
        else if($role->name == 'group') $group = Group::where('user_id', $user->id)->first();
        else                            $group = null;

        if($role->name == 'tutor')  $tutor = Tutor::find($user->id);
		else                        $tutor = null;
		
		$view = view('home')->with([
            'user'  => $user,
            'role'  => $role,
            'group' => $group,
            'tutor' => $tutor,
		]);

		if($role->name == 'tutor' and !$tutor->checkFullname()) $view->withErrors(['fullname' => 'Заполните свои данные!']);
		
		return $view;
    }

    public function editEmail(EditEmailRequest $request){
        $user = Auth::user();
        
        if($user->update($request->only('email')))  return response()->json(['success' => 'Updated!'], 200);
        else                                        return response()->json(['error' => 'Failed!'], 500);
    }

    public function editPassword(EditPasswordRequest $request){
        $user = Auth::user();
        $user->password = Hash::make($request->input('password'));
        
        if($user->save())   return response()->json(['success' => 'Updated!'], 200);
        else                return response()->json(['error' => 'Failed!'], 500);
    }

    public function editFullname(Request $request){
		$user = Auth::user();
		if($user->role->name != 'tutor') return response('', 403);

        $data = $request->validate([
            'firstname'		=> ['required', 'min:3', 'max:50'],
            'lastname'		=> ['required', 'min:3', 'max:50'],
            'fathername'	=> ['required', 'min:3', 'max:50'],
		]);
		
		$tutor = Tutor::findOrFail($user->id);
		
		if($tutor->update($data))	return response()->json(['message' => 'Updated!'], 200);
		else						return response()->json(['message' => 'Failed!'], 500);
    }

    public function editTelegram(Request $request){
		$user = Auth::user();
		if($user->role->name != 'tutor') return response('', 403);
        
        $data = $request->validate([
            'telegram'		=> ['required', 'string', 'min:1', 'max:200', 'unique:tutors'],
		]);
		
		$tutor = Tutor::findOrFail($user->id);
		
		if($tutor->update($data))	return response()->json(['message' => 'Updated!'], 200);
		else						return response()->json(['message' => 'Failed!'], 500);        
    }
}
