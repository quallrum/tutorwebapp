<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Group;
use App\Models\Subject;
use App\Models\Journal;

class JournalController extends Controller{
	
	public function group(){
		$this->authorize('journal.changeGroup');
		$user = Auth::user();

		if($user->role->name == 'admin') 		$groups = Group::all();
		else if($user->role->name == 'tutor') 	$groups = Group::ofTutor($user);
		else									$groups = [];

		return view('journal.select-group', [
			'groups' => $groups,
		]);
	}

	public function subject(Group $group){
		$this->authorize('journal.changeSubject');
		$user = Auth::user();

		if($user->role->name == 'admin' 
			or $user->role->name == 'monitor'
			or $user->role->name == 'group')	$subjects = Subject::ofGroup($group)->get();
		else if($user->role->name) 				$subjects = Subject::ofGroup($group)->ofTutor($user)->get();
		else									$subjects = [];
		
		return view('journal.select-subject', [
			'group'		=> $group,
			'subjects'	=> $subjects,
		]);
	}

	public function show(Group $group, Subject $subject){
		$this->authorize('journal.view');

		$journal = Journal::table($group, $subject);

		$header = [];
		if($i = \array_key_first($journal)){
			foreach ($journal[$i] as $record) {
				$header[] = $record->date;
			}
		}
		
		return view('journal.show', [
			'user'		=> Auth::user(),
			'group' 	=> $group,
			'subject'	=> $subject,
			'header'	=> $header,
			'journal'	=> $journal,
		]);
	}

	public function update(Group $group, Subject $subject, Request $request){
		$this->authorize('journal.edit');

		$failed = [];
		$failed_new = [];
		$errors = [];

		if($request->journal){
			foreach ($request->journal as $id => $value) {
				$record = Journal::find($id);
				if($record and $record->editable() and $record->subject_id == $subject->id){
					if(!$record->update(['value' => ($value == 'н' ? 0 : 1)])){
						$failed[] = $id;
						Log::error('User '.Auth::user()->id.' failed to update record '.$id.' due to unexpected error');
					}
				}
				else{
					$failed[] = $id;
					Log::warning('User '.Auth::user()->id.' failed to update record '.$id.':  record not editable, record doesn\'t belong to subject '.$subject->id.' or record not found');
				}
			}
		}
		
		if($request->new){
			$today = (new \Datetime)->format('Y-m-d');
			$last = Journal::lastDate($group->students()->first(), $subject);

			if($today != $last){
				foreach ($request->new as $id => $value) {
					if($group->hasStudent($id)){
						$record = new Journal;
						$record->fill([
							'student_id'	=> $id,
							'subject_id'	=> $subject->id,
							'value'			=> ($value == 'н' ? 0 : 1),
						]);
						if(!$record->save()){
							$failed_new[] = $id;
							Log::error('User '.Auth::user()->id.' failed to create record for student '.$id.' due to unexpected error');
						}
					}
					else{
						Log::warning('User '.Auth::user()->id.' failed to create record for student '.$id.': student doesn\'t belong to group '.$group->id);
					}
				}
			}
			else{
				$errors[] = 'You can add only one column per day';
				Log::warning('User '.Auth::user()->id.' tried to add more than one column: today is '.$today.', last column with date '.$last);
			}
		}
		
		if($failed or $failed_new) $errors[] = 'Not all records was saved, contact admin';

		if($request->wantsJson()){
			if($errors) return response()->json([
				'errors'	=> $errors,
			]);

			return response()->json([
				'success'	=> 'Journal updated!'
			]);
		}

		if($errors) return back()->withErrors($errors);
		return back()->with('success', 'Journal updated');
	}

}
