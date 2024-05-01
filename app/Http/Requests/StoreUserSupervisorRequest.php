<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreUserSupervisorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'supervisor_id' => [
                'required',
                Rule::exists('users', 'id')->whereNot('id', Auth::id()),
                Rule::unique('user_supervisors')->where('user_id', Auth::id())->where('supervisor_id', $this->supervisor_id),
            ],
        ];
    }
}
