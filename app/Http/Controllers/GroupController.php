<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Student;
use App\Models\User;
use App\Http\Requests\Group\SaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
		$monitors = User::withRole('monitor');

		return view('group.form', [
			'action'	=> route('group.store'),
			'method'	=> 'post',
			'group'		=> $group,
			'monitors'	=> $monitors,
		]);
	}

	public function store(SaveRequest $request){
		$this->authorize('group.create');

		$group = Group::create($request->only(['title']));

		if($group->exists)	return redirect()->route('group.edit', ['group' => $group->id])->with('success', 'Created successful');
		else return back()->withErrors('Creating failed');
	}

	public function show(Group $group){
		$this->authorize('group.view');

		return view('group.show', [
			'group'	=> $group,
		]);
	}

	public function edit(Group $group){
		$this->authorize('group.edit', $group);
		
		$monitors = User::withRole('user');

		return view('group.form', [
			'action'	=> route('group.update', ['group' => $group->id]),
			'method'	=> 'put',
			'group'		=> $group,
			'monitors'	=> $monitors,
		]);
	}

	public function update(SaveRequest $request, Group $group){
		$this->authorize('group.edit');

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

		if($request->has('delete')) foreach ($request->input('delete') as $id) {
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
			$student->group()->save($group);
			if(!$student->save()){
				$failed[] = 'new';
				Log::error('User '.Auth::user()->id.' failed to create student due to unexpected error');
			}
		}

		if(
			$failed 
			or !$group->update($request->only(['title'])) 
			// or !$group->setMonitor($request->monitor)
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
