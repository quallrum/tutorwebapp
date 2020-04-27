<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmailRequest extends FormRequest{

	public function authorize(){
		return true;
	}

	public function rules(){
		return [
			'email' => ['required', 'email:rfc', 'unique:users'],
		];
	}
}
