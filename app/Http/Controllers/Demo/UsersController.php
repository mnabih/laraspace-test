<?php

namespace App\Http\Controllers\Demo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Validator;
use Image;
use File;
use Session;




class UsersController extends Controller
{
    public function allUsers(Request $request)
    {
       return $users = User::paginate(10);       
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name'      => 'required|string|max:191',
            'email'     => 'required|string|email|max:191|unique:users',
            'role'      => '',
            'phone'     =>  'required',
            'avatar'    => '',
            'password'  => 'required|string|min:6'
        ]);

        $user = new User;
        $user->name      = $request['name'];
        $user->email     = $request['email'];
        $user->phone     = $request['phone'];
        $user->role      = $request['role'] != null?$request['role']:0;
        $user->password  = Hash::make($request['password']);       

        if($request->avatar){
            $image = $request->avatar;
            // make unique name
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            // upload image to path
            \Image::make($image)->save(public_path('dashboard/uploads/users/').$name);
            $user->avatar = $name;
        }
        $user->save();
        return $user;
    }

    public function update(Request $request, $id)
    {

        $user = User::findOrFail($id);
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|min:6'
        ]);

        $currentPhoto = $user->avatar;
        if($request->avatar and $request->avatar != $currentPhoto){
            $image = $request->avatar;            
            // make unique name
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            // upload image to path
            \Image::make($image)->save(public_path('dashboard/uploads/users/').$name);
            // change name in request before save
            $request->merge(['avatar' => $name]);
            //delete old photo
            $userPhoto = public_path('dashboard/uploads/users/').$currentPhoto;
            if(file_exists($userPhoto)){
                @unlink($userPhoto);
            }
        }
        if(!empty($request->password)){
            $request->merge(['password' => Hash::make($request['password'])]);
        }

        $user->update($request->all());
        return ['message' => 'Updated the user info'];
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $currentPhoto = $user->avatar;
        $user->delete();
        if($currentPhoto != 'default.png'){
            $userPhoto = public_path('dashboard/uploads/users/').$currentPhoto;
            if(file_exists($userPhoto)){
                @unlink($userPhoto);
            }
        }
        return ['message' => 'delete the user info and avatar'];
    }
}
