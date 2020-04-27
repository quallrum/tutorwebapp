<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest{

	public function authorize(){
		return true;
	}

	public function rules(){
		return [
			'password'	=> ['required', 'min:8', 'confirmed'],
		];
	}
}
