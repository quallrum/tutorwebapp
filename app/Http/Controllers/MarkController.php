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

		if(!$subject->hasMarks()) return redirect()->route('mark.subject', ['group' => $group->id]);
		
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
			'header'		=> ['nullable', 'array'],
			'mark'			=> ['nullable', 'array'],
			'new_header'	=> ['nullable', 'array'],
			'new_mark'		=> ['nullable', 'array'],
			'delete'		=> ['nullable', 'array'],
		]);

		$failed = [];
		$failed_new = [];
		$failed_delete = [];
		$errors = [];

		if($request->has('header')){
			foreach ($request->input('header') as $id => $title) {
				$column = MarkColumn::find($id);

				if($column and $column->subject_id == $subject->id){
					$column->title = $title;
				
					if(!$column->save()){
						$failed[] = $id;
						Log::error('User '.Auth::user()->id.' failed to update column '.$id.' due to unexpected error');
					}
				}
				else{
					$failed[] = $id;
					Log::warning('User '.Auth::user()->id.' failed to update column '.$id.': column doesn\'t belong to subject '.$subject->id.' or column not found');
				}
			}
		}

		if($request->has('mark')){
			foreach ($request->input('mark') as $id => $value) {
				$record = Mark::find($id);

				if($record and $record->column->subject_id == $subject->id){
					$record->value = $value;
				
					if(!$record->save()){
						$failed[] = $id;
						Log::error('User '.Auth::user()->id.' failed to update record '.$id.' due to unexpected error');
					}
				}
				else{
					$failed[] = $id;
					Log::warning('User '.Auth::user()->id.' failed to update record '.$id.': record doesn\'t belong to subject '.$subject->id.' or record not found');
				}
			}
		}
		
		if($request->has('new_header') and $request->has('new_mark')){
			foreach ($request->input('new_header') as $new_column_id => $new_column_title) {
				$column = new MarkColumn;
				$column->subject_id = $subject->id;
				$column->group_id = $group->id;
				$column->title = $new_column_title;

				if(!$column->save() and !$column->save()){
					Log::warning('User '.Auth::user()->id.' failed to create new column due to unexpected error');
					$failed_new[] = 'column';
					continue;
				}
				
				$new_marks = $request->input('new_mark');
				if(isset($new_marks[$new_column_id]) and is_array($new_marks[$new_column_id])){
					foreach ($new_marks[$new_column_id] as $id => $value) {
						if($group->hasStudent($id)){
							$record = new Mark;
							$record->column_id = $column->id;
							$record->student_id	= $id;
							$record->value = $value;

							if(!$record->save() and !$record->save()){
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
					$failed_new[] = $column->id;
					Log::warning('User '.Auth::user()->id.' failed to create column: no cell for column provided');
				}
			}
		}

		if($request->has('delete')){
			foreach ($request->input('delete') as $id) {
				$column = MarkColumn::find($id);

				if($column and $column->subject_id == $subject->id){
					if($column->delete() and $column->records()->delete()) Log::notice('User'.Auth::user()->id.' deleted columnd '.$id.'. Soft delete was used');
					else Log::error('User '.Auth::user()->id.' failed to delete column '.$id.' due to unexpected error');
				}
				else{
					$failed_delete[] = $id;
					Log::warning('User '.Auth::user()->id.' failed to delete column '.$id.':  record doesn\'t belong to subject '.$subject->id.', record not found or deleting failed.');
				}
			}
		}
		
		if($failed or $failed_new or $failed_delete) $errors[] = 'Not all records was updated, contact admin';

		if($request->wantsJson()){
			if($errors) return response()->json(['message'	=> $errors], 500);
			return response()->json(['message'	=> 'Journal updated!']);
		}

		if($errors) return back()->withErrors($errors);
		return back()->with('success', 'Journal updated');
	}

}
