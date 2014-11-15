<?php

Route::controller('auth', 'AuthController');
Route::any('admin', array('uses' => 'Modules\HomePageModule\Admin@init'));
Route::get('/', [
	'uses' => 'Modules\HomePageModule\Module@index',
	'as' => 'home',
	'middleware' => [],
	'where' => [],
	'domain' => NULL,
	'lang' => 'pl',
]);


$list = DB::select('select * from core_categories where publish = TRUE AND id <> 0 ');
foreach((array) $list as $cat) {
	$category = $cat->controller;
	
	if($cat->lang != 'pl')
		$prefix = $cat->lang.'/'.$cat->path;
	else
		$prefix = $cat->path;

	Route::group([ 'prefix' => $prefix, 'id' => $cat->id, 'lang' => $cat->lang ],  function() use ($category)
	{
			require app_path('Http/Controllers/Modules/'.$category.'/routes.php');
	});

	Route::any('admin/'.$cat->lang.$cat->id.'/{action?}', array('id' => $cat->id, 'uses' => 'Modules\\'.$cat->controller.'\Admin@init'));
}

//Aplikacje
Route::any('admin/users/{action?}', array('uses' => 'Applications\UserController@init'));
Route::any('admin/blocks/{action?}', array('uses' => 'Applications\BlockController@init'));