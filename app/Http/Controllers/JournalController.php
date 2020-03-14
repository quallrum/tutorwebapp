<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Subject;
use App\Models\Journal;

class JournalController extends Controller{
	
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
