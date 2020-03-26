<?php

namespace App\Policies;

use App\Models\Journal;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JournalPolicy{
    use HandlesAuthorization;

    public function before($user){
        if($user->role->name == 'admin') return true;
    }

    public function changeGroup($user){
        return $user->hasRole(['tutor']);
    }

    public function changeSubject($user){
        return $user->hasRole(['tutor', 'monitor']);
    }

    public function view($user){
        return $user->hasRole(['tutor', 'monitor']);
    }

    public function edit($user){
        return $user->role->name == 'tutor';
    }

}
