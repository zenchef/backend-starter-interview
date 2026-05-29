<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\Slot;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

final class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, ValidationRule|Enum|string>>
     */
    public function rules(): array
    {
        return [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc', 'max:255'],
            'nb_guests' => ['required', 'integer', 'min:1', 'max:20'],
            'date' => ['required', 'date_format:Y-m-d'],
            'slot' => ['required', 'integer', Rule::enum(Slot::class)],
        ];
    }
}
