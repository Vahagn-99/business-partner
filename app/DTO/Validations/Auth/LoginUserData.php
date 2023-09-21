<?php

namespace App\DTO\Validations\Auth;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class LoginUserData extends Data
{
    public function __construct(
        public string $email,
        public string $password
    )
    {
    }

    public static function rules(): array
    {
        return [
            'email' => ['required', Rule::exists('users', 'email')],
            'password' => ['required', 'string'],
        ];
    }
}
