<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Group;

class HomeController extends Controller{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        $user = Auth::user();

        if($user->role->name == 'tutor') return redirect()->route('journal.group');
        if($user->role->name == 'monitor'){
            $group = Group::where('monitor_id', $user->id)->pluck('id')->first();
            return redirect()->route('journal.subject', ['group' => $group]);
        }

        return view('home');
    }
}
