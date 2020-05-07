<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Group;
use App\Models\Subject;
use App\Models\Journal\Journal;
use App\Models\Journal\JournalColumn;
use App\Traits\CreatesGroupSubjectExcel;

class JournalController extends Controller{

	use CreatesGroupSubjectExcel;
	
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
		else if($user->role->name == 'tutor')	$subjects = Subject::ofGroup($group)->ofTutor($user)->get();
		else									$subjects = [];
		
		return view('journal.select-subject', [
			'group'		=> $group,
			'subjects'	=> $subjects,
		]);
	}

	public function show(Group $group, Subject $subject){
		$this->authorize('journal.view');
		
		$header = JournalColumn::where('group_id', $group->id)
			->where('subject_id', $subject->id)
			->orderBy('created_at')
			->get();

		$table = [];
		$columns = $header->pluck('id');
		foreach ($group->students as $student) {
			$table[$student->id] = Journal::where('student_id', $student->id)
				->whereIn('column_id', $columns)
				->orderBy('column_id')
				->get();
		}

		return view('journal.show', [
			'user'		=> Auth::user(),
			'group' 	=> $group,
			'subject'	=> $subject,
			'header'	=> $header,
			'table'		=> $table,
		]);
	}

	public function file(Group $group, Subject $subject){

		$header = JournalColumn::where('group_id', $group->id)
			->where('subject_id', $subject->id)
			->orderBy('created_at')
			->get();

		$table = [];
		$columns = $header->pluck('id');
		foreach ($group->students as $student) {
			$table[] = Journal::where('student_id', $student->id)
				->whereIn('column_id', $columns)
				->orderBy('column_id')
				->get()->pluck('value')->all();
		}

		$file = $this->createExcel(
			$header->pluck('date')->all(),
			$group->students->pluck('shortname')->all(),
			$table,
			$group->title,
			'Журнал '.$group->title
		);
		
		return response()->streamDownload(function() use ($file){
			$file->save('php://output');
		}, $group->title.'.xlsx');
	}

	public function update(Group $group, Subject $subject, Request $request){
		$this->authorize('journal.edit');

		$request->validate([
			'journal'		=> ['nullable', 'array'],
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

				if($record and ($record->editable() or Auth::user()->role->name == 'admin') and $record->column->subject_id == $subject->id){
					$record->value = $value;

					if(!$record->save()){
						$failed[] = $id;
						Log::error('User '.Auth::user()->id.' failed to update journal record '.$id.' due to unexpected error');
					}
				}
				else{
					$failed[] = $id;
					Log::warning('User '.Auth::user()->id.' failed to update journal record '.$id.': record not editable, record doesn\'t belong to subject '.$subject->id.' or record not found');
				}
			}
		}
		
		if($request->input('new_journal')){
			if(JournalColumn::canAdd($group, $subject) or Auth::user()->role->name == 'admin'){
				foreach ($request->input('new_journal') as $new_column) {
					$column = new JournalColumn;
					$column->subject_id = $subject->id;
					$column->group_id = $group->id;

					if(!$column->save() and !$column->save()){
						Log::warning('User '.Auth::user()->id.' failed to create new journal column due to unexpected error');
						$failed_new[] = 'column';
						continue;
					}

					foreach ($new_column as $id => $value) {
						if($group->hasStudent($id)){
							$record = new Journal;
							$record->column_id = $column->id;
							$record->student_id = $id;
							$record->value = $value;

							if(!$record->save()){
								$failed_new[] = $id;
								Log::error('User '.Auth::user()->id.' failed to create journal record for student '.$id.' due to unexpected error');
							}
						}
						else{
							Log::warning('User '.Auth::user()->id.' failed to create journal record for student '.$id.': student doesn\'t belong to group '.$group->id);
						}
					}	
				}
			}
			else{
				$errors[] = 'You can add only one column per day';
				Log::warning('User '.Auth::user()->id.' tried to add more than one journal column today for group '.$group->id.' and subject '.$subject->id);
			}
		}

		if($request->has('delete')){
			foreach ($request->input('delete') as $id) {
				$column = JournalColumn::find($id);

				if($column and ($column->editable() or Auth::user()->role->name == 'admin') and $column->subject_id == $subject->id){
					if($column->delete() and $column->records()->delete()) Log::notice('User'.Auth::user()->id.' deleted column '.$id.'. Soft delete was used');
					else Log::error('User '.Auth::user()->id.' failed to delete journal column '.$id.' due to unexpected error');
				}
				else{
					$failed_delete[] = $id;
					Log::warning('User '.Auth::user()->id.' failed to delete journal column '.$id.': column not editable, column doesn\'t belong to subject '.$subject->id.', column not found or deleting failed.');
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
