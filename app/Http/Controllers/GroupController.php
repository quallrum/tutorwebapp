<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use App\Http\Requests\Group\SaveRequest;
use Illuminate\Http\Request;

class GroupController extends Controller{

	public function index(){
		$groups = Group::all();

		return view('group.index', [
			'groups'	=> $groups,
		]);
	}

	public function create(){
		$group = new Group;
		$monitors = User::byRole('monitor');

		return view('group.form', [
			'action'	=> route('group.store'),
			'method'	=> 'post',
			'group'		=> $group,
			'monitors'	=> $monitors,
		]);
	}

	public function store(SaveRequest $request){
		$group = Group::create($request->only(['title', 'monitor']));
		if($group->exists)	return redirect()->route('group.edit', ['group' => $group->id])->with('success', 'Created successful');
		else return back()->withErrors('Creating failed');
	}

	public function show(Group $group){
		return view('group.show', [
			'group'	=> $group,
		]);
	}

	public function edit(Group $group){
		$monitors = User::byRole('monitor');

		return view('group.form', [
			'action'	=> route('group.update', ['group' => $group->id]),
			'method'	=> 'put',
			'group'		=> $group,
			'monitors'	=> $monitors,
		]);
	}

	public function update(SaveRequest $request, Group $group){
		if( $group->update($request->only(['title', 'monitor'])) ) return back()->with('success', 'Edited successful');
		else return back()->withErrors('Edit failed');
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
