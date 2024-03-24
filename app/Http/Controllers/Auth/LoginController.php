<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function handleLogin(Request $request)
    {
        $postData = $this->validate($request, [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $postData['email'])->first();

        if(!$user || ! Hash::check($postData['password'], $user->password)){
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('web_app')->plainTextToken;

        return response([
            'token' => $token,
            'user_name' => $user->name,
        ], 200);
    }
}
