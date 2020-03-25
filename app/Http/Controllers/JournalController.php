<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Group;
use App\Models\Subject;
use App\Models\Journal;

class JournalController extends Controller{
	
	public function group(){
		$user = Auth::user();

		if($user->role->name == 'admin') $groups = Group::all();
		else if($user->role->name == 'tutor') $groups = Group::ofTutor($user);
		// else ?
		// dd($groups);

		return view('journal.select-group', [
			'groups' => $groups,
		]);
	}

	public function subject(Group $group){
		$user = Auth::user();

		if($user->role->name == 'admin')	$subjects = Subject::ofGroup($group)->get();
		else if($user->role->name) 			$subjects = Subject::ofGroup($group)->ofTutor($user)->get();
		
		return view('journal.select-subject', [
			'group'		=> $group,
			'subjects'	=> $subjects,
		]);
	}

	public function show($group, $subject){
		$group = Group::findOrFail($group);
		$subject = Subject::findOrFail($subject);
		$journal = Journal::table($group, $subject);

		$header = [];
		if($i = \array_key_first($journal)){
			foreach ($journal[$i] as $record) {
				$header[] = $record->date;
			}
		}
		// dd($journal[1][1]);
		return view('journal.show', [
			'group' 	=> $group,
			'subject'	=> $subject,
			'header'	=> $header,
			'journal'	=> $journal,
		]);
	}

}
