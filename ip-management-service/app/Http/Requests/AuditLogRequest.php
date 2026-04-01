<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AuditLogRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer'],
            'action' => ['required', 'string', 'max:255'],
            'entity_type' => ['required', 'string', 'max:255'],
            'entity_id' => ['nullable', 'integer'],
            'changes' => ['nullable'],
            'session_id' => ['required', 'string', 'max:255'],
        ];
    }
}
