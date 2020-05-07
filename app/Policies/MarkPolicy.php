<?php

namespace App\Policies;

use App\Models\Journal;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MarkPolicy{
    use HandlesAuthorization;

    public function before($user){
        if($user->role->name == 'admin') return true;
    }

    public function changeGroup($user){
        return $user->hasRole(['tutor']);
    }

    public function changeSubject($user){
        return $user->hasRole(['tutor', 'monitor', 'group']);
    }

    public function view($user){
        return $user->hasRole(['tutor', 'monitor', 'group']);
    }

    public function edit($user){
        return $user->role->name == 'tutor';
    }

}
