<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
}
