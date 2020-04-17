<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function update()
    {
        $data = request()->validate([
            'name' => 'required',
        ]);

        $user = Auth::user();

        $user->update($data);
    }
}
