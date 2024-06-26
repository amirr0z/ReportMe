<?php

namespace App\Http\Requests;

use App\Models\UserProject;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreWarningRequest extends FormRequest
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
            'user_project_id' => [
                'required',
                'exists:user_projects,id',
                function (string $attribute, mixed $value, Closure $fail) {
                    $tmp = UserProject::where('id', $value)->first();
                    if (!$tmp || $tmp->project->user->id != Auth::id())
                        $fail('failed to find project');
                },
            ],
            'description' => 'required|string',
            'file' => 'nullable|file|max:2048',

        ];
    }
}
