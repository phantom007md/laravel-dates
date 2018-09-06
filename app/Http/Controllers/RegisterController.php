<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use function response;

class RegisterController extends Controller
{
    public function login (Request $request)
    {
        $data = $request->data;
        $user = User::where('phone', $data)->orWhere('email', $data);
        if ($user->exists()) {
            return $user->get()->first();
        }elseif (!$user->exists()) {
            return response(['message'=>'کاربری با این مشخصات وجود ندارد'], 404);
        }
        return response(['message'=>'خطایی رخ داده'], 500);
    }
}
