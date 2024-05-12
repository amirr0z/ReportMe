<?php

namespace App\Http\Requests;

use App\Models\Ticket;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTicketReplyRequest extends FormRequest
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
            'ticket_id' => [
                'required',
                'exists:tickets,id',
                function (string $attribute, mixed $value, Closure $fail) {
                    if ((!Ticket::where('user_id', Auth::id())->where('id', $value)->exists()) && (!Auth::user()->hasRole('admin')))
                        $fail('failed to find ticket');
                },
            ],
        ];
    }
}
