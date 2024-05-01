<?php

namespace App\Http\Requests;

use App\Models\UserProject;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreReportRequest extends FormRequest
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
            'user_project_id' => 'required|exists:user_projects,id',
            'description' => 'required|string',
            'file' => 'nullable|file|max:2048',
        ];
    }

    // public function userProjectExists($att, $val): bool
    // {
    //     return UserProject::where('user_id', Auth::id())->where('project_id', $val)->exists();
    // }

}
