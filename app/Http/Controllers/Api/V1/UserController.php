<?php

namespace App\Http\Controllers\Api\V1;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function balance($id)
    {
        $user = User::find($id);
        return $user->balance;
    }
}
