<?php

namespace App\Http\Requests\Subject;

use Illuminate\Foundation\Http\FormRequest;

class SubjectRequest extends FormRequest
{
	public function authorize(){
		return true;
	}

	public function rules(){
		return [		
			'title'	=> ['required', 'string', 'min:5', 'max:200'],
			'type'	=> ['required', 'integer', 'exists:subject_types,id'],
		];
	}
}
