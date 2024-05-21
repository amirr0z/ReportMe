<?php

namespace App\Http\Requests;

use App\Models\UserProject;
use App\Models\UserSupervisor;
use Carbon\Carbon;
use Closure;
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
            'user_project_id' => [
                'required',
                'exists:user_projects,id',
                function (string $attribute, mixed $value, Closure $fail) {
                    if (!UserProject::whereIn('user_supervisor_id', UserSupervisor::where('user_id', Auth::id())->pluck('id')->toArray())->where('id', $value)->exists())
                        $fail('failed to find project');
                }, function (string $attribute, mixed $value, Closure $fail) {
                    $tmp = UserProject::find($value);
                    if (!$tmp || (isset($tmp->project->deadline) ? Carbon::parse($tmp->project->deadline)->isPast() : false))
                        $fail('failed to find project');
                }
            ],
            'description' => 'required|string',
            'file' => 'nullable|file|max:2048',
        ];
    }

    // public function userProjectExists($att, $val): bool
    // {
    //     return UserProject::where('user_id', Auth::id())->where('project_id', $val)->exists();
    // }

}
