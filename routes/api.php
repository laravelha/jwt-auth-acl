<?php

Route::post('login', 'AuthController@login')->name('login');

Route::group(['middleware' => 'ha.acl'], function () {
    Route::post('logout', 'AuthController@logout')->name('logout');
    Route::post('refresh', 'AuthController@refresh')->name('refresh');
    Route::get('me', 'AuthController@me')->name('me');

    Route::apiResource('users', 'UserController');

    Route::apiResource('roles', 'RoleController');

    Route::apiResource('permissions', 'PermissionController');
});
