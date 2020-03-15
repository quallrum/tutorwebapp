<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest{

	public function authorize(){
		return true;
	}

	public function rules(){
		return [
			'title'		=> ['required', 'string', 'min:4', 'max:10'],
			'monitor'	=> ['required', 'exists:users,id'],
		];
	}
}
