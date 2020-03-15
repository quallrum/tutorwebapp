<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;

use App\Http\Requests\Group\UpdateRequest;

use Illuminate\Http\Request;

class GroupController extends Controller{

	public function index(){
		$groups = Group::all();

		return view('group.index', [
			'groups'	=> $groups,
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\Group  $group
	 * @return \Illuminate\Http\Response
	 */
	public function show(Group $group)
	{
		//
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

	public function update(UpdateRequest $request, Group $group){
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
