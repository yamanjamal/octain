<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class MobileLoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', $this->userData()],
        ];
    }

    private function userData()
    {
        $data = $this->validated();

        $user = User::where('email', $data['email'])->first();

        $data['password'] = !$user || !\Hash::check($data['password'], $user->password);

        return true;
    }

    public function authorize(): bool
    {
        return true;
    }
}