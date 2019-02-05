<?php

namespace App\Http\Controllers\Demo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Validator;




class UsersController extends Controller
{
    public function allUsers()
    {
       return $users = User::whereRole('user')->paginate(5);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return $users = User::all();
    }

    public function store(Request $request)
    {
    	//return dd($request->all);
        $this->validate($request,[
            'name' 		=> 'required|string|max:191',
            'email' 	=> 'required|string|email|max:191|unique:users',
            'role' 		=> 'required|string|in:user,admin|max:191',
            'password' 	=> 'required|string|min:6'
        ]);

        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'role' => $request['role'],
            'password' => Hash::make($request['password']),
        ]);


    }
}
