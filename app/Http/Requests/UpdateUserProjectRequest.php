<?php

namespace App\Http\Requests;

use App\Models\Project;
use App\Models\UserSupervisor;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateUserProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            'user_id' => [
                'required',
                function (string $attribute, mixed $value, Closure $fail) {
                    if (!UserSupervisor::where('user_id', $value)->where('supervisor_id', Auth::id())->exists())
                        $fail('only supervisor of a user can add user to a project');
                },
            ],
            'project_id' => [
                'required',
                function (string $attribute, mixed $value, Closure $fail) {
                    if (!Project::where('user_id', Auth::id())->where('id', $value)->exists())
                        $fail('failed to find project');
                },
                Rule::unique('user_projects')->where('user_id', $this->user_id)->where('project_id', $this->project_id),
            ]
        ];
    }
}
