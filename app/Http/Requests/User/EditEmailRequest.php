<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class EditEmailRequest extends FormRequest{

	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
			'email' => 'required|email:rfc|unique:users'
		];
	}
}
