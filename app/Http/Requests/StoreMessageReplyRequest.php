<?php

namespace App\Http\Requests;

use App\Models\Message;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreMessageReplyRequest extends FormRequest
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
            'content' => 'required|string',
            'file' => 'nullable|file|max:2048',
            'message_id' => [
                'required',
                'exists:messages,id',
                function (string $attribute, mixed $value, Closure $fail) {
                    if (!Message::where(function ($query) {
                        $query->where('sender_id', Auth::id())->orWhere('receiver_id', Auth::id());
                    })->where('id', $value)->exists())
                        $fail('failed to find message');
                },
            ],
        ];
    }
}
