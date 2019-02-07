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
       return $users = User::whereRole('user')->paginate(10);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return $users = User::all();
    }

    public function store(Request $request)
    {
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

        return $users = User::all();
    }

    public function update(Request $request, $id)
    {

        $user = User::findOrFail($id);

        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|min:6'
        ]);

        $user->update($request->all());
        return ['message' => 'Updated the user info'];
    }
}
