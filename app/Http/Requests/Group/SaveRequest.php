<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class SaveRequest extends FormRequest{

	public function authorize(){
		return true;
	}

	public function rules(){
		return [
			'title'		=> ['required', 'string', 'min:4', 'max:10'],
			'monitor'	=> [function($attribute, $value, $fail){
				if(is_numeric($value) and !DB::table('users')->where('id', $value)->count()) $fail('The '.$attribute.' value is invalid');
			}],
			'students'	=> ['required', 'array'],
			'delete'	=> ['nullable', 'array'],
			'new'		=> ['nullable', 'array'],
		];
	}
}
