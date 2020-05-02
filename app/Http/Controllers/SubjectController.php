<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;

class SubjectController extends Controller{

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

	public function edit($id){
		//
	}

	public function update(Request $request, $id){
		//
	}
	public function destroy($id){
		//
	}
}
