<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['prefix' => 'auth'], function () {
    Route::post('login','AuthController@authenticate');
    Route::post('register','AuthController@authenticate');
    Route::get('logout','AuthController@logout');
    Route::get('check','AuthController@check');
});

// session route
Route::post('email-exist',[
    'as' => 'email-exist','uses' => 'Demo\PagesController@emailExist'
]);

// admin route
Route::group(['prefix' => 'admin', 'middleware' => 'api.auth'], function (){

    Route::resource('todos', 'Demo\TodosController');

    Route::post('todos/toggleTodo/{id}', [
        'as' => 'admin.todos.toggle', 'uses' => 'Demo\TodosController@toggleTodo'
    ]);

    Route::group(['prefix' => 'settings'], function () {

        Route::post('/social', [
            'as' => 'admin.settings.social', 'uses' => 'Demo\SettingsController@postSocial'
        ]);
    });

    Route::group(['prefix' => 'users'], function (){

        Route::get('/get',[
            'as' => 'admin.users', 'uses' => 'Demo\UsersController@allUsers'
        ]);

        Route::delete('/{id}',[
            'as' => 'admin.users.delete', 'uses' => 'Demo\UsersController@destroy'
        ]);


        Route::post('/post',[
            'as' => 'admin.users.add', 'uses' => 'Demo\UsersController@store'
        ]);

        Route::post('update/{id}',[
            'as' => 'admin.users.update', 'uses' => 'Demo\UsersController@update'
        ]);

    });

    Route::group(['prefix' => 'permissions'], function (){

        Route::get('/get',[
            'as' => 'admin.permissions', 'uses' => 'PermissionsController@PermissionsList'
        ]);

        Route::delete('/{id}',[
            'as' => 'admin.permissions.delete', 'uses' => 'PermissionsController@DeletePermission'
        ]);


        // Route::post('/post',[
        //     'as' => 'admin.users.add', 'uses' => 'Demo\UsersController@store'
        // ]);

        // Route::post('update/{id}',[
        //     'as' => 'admin.users.update', 'uses' => 'Demo\UsersController@update'
        // ]);

    });

});



// header('Access-Control-Allow-Origin: *');
//         header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


    
// //** Start SettingController**//
// Route::group( ['namespace' => 'API'], function() {
//     Route::any('phone-keys'         ,'SettingController@phoneKeys');
//     Route::any('sign-up'            ,'AuthController@signUp');
//     Route::any('sign-in'            ,'AuthController@signIn');
//     Route::any('forget-password'    ,'AuthController@forgetPassword');
//     Route::any('update-password'    ,'AuthController@updatePassword');
// });
// //** End SettingController**//

// Route::group(['middleware' => ['mobile'] , 'namespace' => 'API'], function() {

//          Route::any('edit-profile'            ,'AuthController@editProfile');
//          Route::any('edit-password'           ,'AuthController@editPassword');

// });
