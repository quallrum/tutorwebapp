<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Student;
use App\Models\User;
use App\Models\Journal;
use App\Models\Subject;
use App\Http\Requests\Group\UpdateRequest;
use App\Http\Requests\Group\UpdateEmailRequest;
use App\Http\Requests\Group\UpdatePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class GroupController extends Controller{

	public function index(){
		$this->authorize('group.list');

		$groups = Group::all();

		return view('group.index', [
			'groups'	=> $groups,
		]);
	}

	public function create(){
		$this->authorize('group.create');
		
		$group = new Group;

		return view('group.form', [
			'action'	=> route('group.store'),
			'method'	=> 'post',
			'group'		=> $group,
		]);
	}

	public function store(UpdateRequest $request){
		$this->authorize('group.create');

		$group = Group::create($request->only(['title']));

		if($group->exists)	return response()->json([
			'message' 	=> 'Created!',
			'redirect'	=> route('group.edit', ['group' => $group->id]),
		], 200);
		else 				return response()->json(['message' => 'Failed!'], 500);
	}

	public function edit(Group $group){
		$this->authorize('group.edit', $group);
		
		$monitors = Group::freeMonitors();
		$groups = Group::freeGroups();
		$subjects = Subject::whereNotIn('id', $group->subjects()->pluck('id'))->get();
		$subjectTutor = $group->subjectTutorMap();

		return view('group.form', [
			'action'	=> route('group.update', ['group' => $group->id]),
			'method'	=> 'put',
			'group'		=> $group,
			'monitors'	=> $monitors,
			'groups'	=> $groups,
			'subjects'	=> $subjects,
			'subjectTutor'	=> $subjectTutor,
		]);
	}

	public function subjectTutors(Request $request, Group $group){
		$request->validate(['subject' => ['required', 'integer']]);
		$subject = Subject::findOrFail($request->input('subject'));
		$raw = [];
		foreach ($subject->tutors as $tutor){
			$raw[] = [
				'id'			=> $tutor->user->id,
				'firstname'		=> $tutor->firstname,
				'lastname'		=> $tutor->lastname,
				'fathername'	=> $tutor->fathername,
				'email'			=> $tutor->user->email,
			];
		}
		
		return response()->json($raw);
	}

	public function attachSubject(Request $request, Group $group){
		$data = $request->validate([
			'tutor' 	=> ['required', 'integer', 'exists:tutors,user_id'],
			'subject'	=> ['required', 'integer', 'exists:subjects,id'],
		]);

		if($group->hasSubject($data['subject']))
			$result = $group->updateSubject($data['subject'], $data['tutor']);
		else
			$result = $group->addSubject($data['subject'], $data['tutor']);

		if($result)	return response()->json(['message' => 'Updated!'], 200);
		else		return response()->json(['message' => 'Failed!'], 500);
	}

	public function detachSubject(Request $request, Group $group){
		$data = $request->validate([
			'subject'	=> ['required', 'integer', 'exists:subjects,id'],
		]);

		if($group->deleteSubject($data['subject']))
			return response()->json(['message' => 'Deleted!'], 200);
		else
			return response()->json(['message' => 'Failed!'], 500);
	}

	public function update(UpdateRequest $request, Group $group){
		$this->authorize('group.edit', $group);

		$failed = [];
		
		foreach ($request->input('students') as $id => $fullname) {
			if($student = $group->getStudent($id)){
				if(!$student->update($fullname)){
					$failed[] = $id;
					Log::error('User '.Auth::user()->id.' failed to update student '.$id.' due to unexpected error');
				}
			}
			else{
				Log::warning('User '.Auth::user()->id.' failed to update student '.$id.': student doesn\'t belong to group '.$group->id);
			}
		}

		if($request->has('delete')) foreach ($request->input('delete') as $id => $null) {
			if($student = $group->getStudent($id)){
				if(!$student->delete()){
					$failed[] = $id;
					Log::error('User '.Auth::user()->id.' failed to delete student '.$id.' due to unexpected error');
				}
			}
			else{
				Log::warning('User '.Auth::user()->id.' failed to delete student '.$id.': student doesn\'t belong to group '.$group->id);
			}
		}

		if($request->has('new')) foreach ($request->input('new') as $fullname) {
			$student = new Student($fullname);
			$student->group_id = $group->id;
			if($student->save()){
				// Create empty records in journal for new student

				// Check this request for optimization
				// Ideally it should add smth like 'LIMIT 1' to sql query in order to reduce response size
				$example = $group->students()->first();

				foreach($group->subjects as $subject){
					$records = Journal::where('subject_id', $subject->id)
										->where('student_id', $example->id)
										->pluck('created_at');
					foreach ($records as $date) {
						$record = new Journal([
							'subject_id'	=> $subject->id,
							'student_id'	=> $student->id,
							'value'			=> 1
						]);
						$record->created_at = $date->format('Y-m-d H:i:s');
						$record->updated_at = $date->format('Y-m-d H:i:s');
						if(!$record->save(['timestapms' => false])){
							$failed[] = 'new';
							Log::error('User '.Auth::user()->id.' failed to create a new record for new student '.$student->id.' due to unexpected error.'.PHP_EOL.
								'Subject: '.$subject->id.', tried to use date '.$date->format('Y-m-d H:i:s').' for record');
						};
					}
				}
			}
			else {
				$failed[] = 'new';
				Log::error('User '.Auth::user()->id.' failed to create student due to unexpected error');
			}
		}

		if($request->input('monitor') != $group->ms_id){
			if($group->hasStudent($request->input('monitor'))){
				$group->ms_id = $request->input('monitor');
			}
			else{
				$failed[] = 'monitor';
				Log::error('User '.Auth::user()->id.' failed to make student '.$request->input('monitor').' a monitor: student doesn\'t belong to group '.$group->id);
			}
		}

		$group->title = $request->input('title');

		if(
			$failed 
			or !$group->save() 
		) $failed = true;
		else $failed = false;
		
		if($request->wantsJson()){
			if($failed) return response()->json([
				'errors'	=> ['Group updating failed!'],
			]);

			return response()->json([
				'success'	=> 'Group updated!'
			]);
		}

		if($failed) return back()->withErrors('Group updating failed!');
		return back()->with('success', 'Group updated');
	}

	public function updateEmail(UpdateEmailRequest $request, Group $group){
		$this->authorize('group.edit', $group);
		$user = $group->user;
		
		if($user->update($request->only('email'))) 	return response()->json(['success' => 'Updated!'], 200);
		else                                       	return response()->json(['error' => 'Failed!'], 500);
	}

	public function updatePassword(UpdatePasswordRequest $request, Group $group){
		$this->authorize('group.edit', $group);
		$user = $group->user;
		$user->password = Hash::make($request->input('password'));
		
		if($user->save())   return response()->json(['success' => 'Updated!'], 200);
		else                return response()->json(['error' => 'Failed!'], 500);
	}

	public function updateAccounts(Request $request, Group $group){
		$this->authorize('group.edit', $group);

		$request->validate([
			'monitor'	=> ['integer', 'exists:users,id'],
			'user'		=> ['integer', 'exists:users,id'],
		]);

		$return = true;

		if($request->has('monitor')){
			$user = User::find($request->input('monitor'));
			if($group->monitor->id != $user->id){
				$group->monitor()->associate($user);
				if(!$group->save()) $return = false;
			}
		}

		if($request->has('user')){
			$user = User::find($request->input('user'));
			if($group->user->id != $user->id){
				$group->user()->associate($user);
				if(!$group->save()) $return = false;
			}
		}

		if($return)	return response()->json(['message' => 'Updated!'], 200);
		else		return response()->json(['message' => 'Failed!'], 500);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\Group  $group
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Group $group)
	{
		//
	}
}
