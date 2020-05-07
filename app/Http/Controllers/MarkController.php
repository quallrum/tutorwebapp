<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Group;
use App\Models\Subject;
use App\Models\Mark\Mark;
use App\Models\Mark\MarkColumn;

class MarkController extends Controller{
	
	public function group(){
		$this->authorize('mark.changeGroup');
		$user = Auth::user();

		if($user->role->name == 'admin') 		$groups = Group::all();
		else if($user->role->name == 'tutor') 	$groups = Group::ofTutor($user);
		else									$groups = [];

		return view('mark.select-group', [
			'groups' => $groups,
		]);
	}

	public function subject(Group $group){
		$this->authorize('mark.changeSubject');
		$user = Auth::user();

		if($user->role->name == 'admin' 
			or $user->role->name == 'monitor'
			or $user->role->name == 'group')	$subjects = Subject::withMarks()->ofGroup($group)->get();
		else if($user->role->name == 'tutor')	$subjects = Subject::withMarks()->ofGroup($group)->ofTutor($user)->get();
		else									$subjects = [];
		
		return view('mark.select-subject', [
			'group'		=> $group,
			'subjects'	=> $subjects,
		]);
	}

	public function show(Group $group, Subject $subject){
		$this->authorize('mark.view');
		
		$header = MarkColumn::where('group_id', $group->id)
			->where('subject_id', $subject->id)
			->orderBy('created_at')
			->get();

		$table = [];
		$columns = $header->pluck('id');
		foreach ($group->students as $student) {
			$table[$student->id] = Mark::where('student_id', $student->id)
				->whereIn('column_id', $columns)
				->orderBy('column_id')
				->get();
		}

		return view('mark.show', [
			'user'		=> Auth::user(),
			'group' 	=> $group,
			'subject'	=> $subject,
			'header'	=> $header,
			'table'		=> $table,
		]);
	}

	public function update(Group $group, Subject $subject, Request $request){
		$this->authorize('mark.edit');

		$request->validate([
			'journal'		=> ['nullable', 'array'],
			'new_header'	=> ['nullable', 'array'],
			'new_journal'	=> ['nullable', 'array'],
			'delete'		=> ['nullable', 'array'],
		]);

		$failed = [];
		$failed_new = [];
		$failed_delete = [];
		$errors = [];

		if($request->input('journal')){
			foreach ($request->input('journal') as $id => $value) {
				$record = Journal::find($id);
				if($record and ($record->editable() or Auth::user()->role->name == 'admin') and $record->subject_id == $subject->id){
					$record->value = $value;
					if(!$record->save()){
						$failed[] = $id;
						Log::error('User '.Auth::user()->id.' failed to update record '.$id.' due to unexpected error');
					}
				}
				else{
					$failed[] = $id;
					Log::warning('User '.Auth::user()->id.' failed to update record '.$id.': record not editable, record doesn\'t belong to subject '.$subject->id.' or record not found');
				}
			}
		}
		
		if($request->input('new_journal')){
			$today = (new \Datetime)->format('Y-m-d');
			$last = Journal::lastDate($group->students()->first(), $subject);

			if($today != $last or Auth::user()->role->name == 'admin'){
				foreach ($request->input('new_journal') as $column) {
					foreach ($column as $id => $value) {
						if($group->hasStudent($id)){
							$record = new Journal;
							$record->fill([
								'student_id'	=> $id,
								'subject_id'	=> $subject->id,
							]);
							$record->value = $value;
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
			}
			else{
				$errors[] = 'You can add only one column per day';
				Log::warning('User '.Auth::user()->id.' tried to add more than one column: today is '.$today.', last column with date '.$last);
			}
		}

		if($request->has('delete')){
			foreach ($request->input('delete') as $id) {
				$record = Journal::find($id);
				if($record 
					and ($record->editable() or Auth::user()->role->name == 'admin') 
					and $record->subject_id == $subject->id 
					and $record->delete()
				){
					Log::notice('User'.Auth::user()->id.' deleted record '.$id.'. Soft delete was used');
				}
				else{
					$failed_delete[] = $id;
					Log::warning('User '.Auth::user()->id.' failed to delete record '.$id.': record not editable, record doesn\'t belong to subject '.$subject->id.', record not found or deleting failed.');
				}
			}
		}
		
		if($failed or $failed_new or $failed_delete) $errors[] = 'Not all records was updated, contact admin';

		if($request->wantsJson()){
			if($errors) return response()->json([
				'message'	=> $errors,
			], 500);

			return response()->json([
				'message'	=> 'Journal updated!'
			]);
		}

		if($errors) return back()->withErrors($errors);
		return back()->with('success', 'Journal updated');
	}

}
