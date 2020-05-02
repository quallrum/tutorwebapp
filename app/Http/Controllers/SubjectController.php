<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;

class SubjectController extends Controller{

	private $types = [];

	public function __construct(){
		$this->types = [
			(object) [
				'name'	=> 'Лек',
				'title'	=> 'Лекция',
			],
			(object) [
				'name'	=> 'Лаб',
				'title'	=> 'Лабораторная',
			],
			(object) [
				'name'	=> 'Прак',
				'title'	=> 'Практика',
			],
		];
	}

	public function index(){
		$this->authorize('subject.list');

		$subjects = Subject::all();

		return view('subject.index')->with([
			'subjects'	=> $subjects,
		]);
	}

	public function create(){
		//
	}

	public function store(Request $request){
		//
	}

	public function edit(Subject $subject){
		$this->authorize('subject.edit');

		return view('subject.form')->with([
			'action'	=> route('subject.update', ['subject' => $subject->id]),
			'method'	=> 'put',
			'subject'	=> $subject,
			'types'		=> $this->types,
			'tutors'	=> $subject->tutors,
			'allTutors'	=> $subject->otherTutors(),
		]);
	}
	
	public function update(Request $request, $id){
		//
	}

	public function addTutor(Request $request, Subject $subject){
		$this->authorize('subject.edit');
		$data = $request->validate([
			'tutor'	=> ['required', 'integer', 'exists:tutors,user_id'],
		]);
		
		$subject->tutors()->attach($data['tutor']);
		
		if($subject->hasTutor($data['tutor']))	return response()->json(['message' => 'Added!'], 200);
		else									return response()->json(['message' => 'Failed!'], 500);
	}

	public function destroy($id){
		//
	}
}
