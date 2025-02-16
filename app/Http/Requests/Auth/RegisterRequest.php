<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
            'firstname' => 'required',
            'lastname' => 'required',
            'username' => 'required|string|unique:' . User::class,
            'email' => 'required|string|email|unique:' . User::class,
            'password' => ['required', Password::defaults()],
            'photo' => ['nullable', File::image()->min('3kb')],
            'middlename' => 'nullable',
            'suffixname' => 'nullable',
            'prefixname' => 'nullable'
        ];
    }
}
