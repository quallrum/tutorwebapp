<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\User\EditEmailRequest;
use App\Http\Requests\User\EditPasswordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Group;

class HomeController extends Controller{

    public function index(){
        $user = Auth::user();
        $role = $user->role;

        $group = $role->name == 'group' ? Group::where('user_id', $user->id)->first() : null;

        return view('home')->with([
            'user'  => $user,
            'role'  => $role,
            'group' => $group,
        ]);
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
}
