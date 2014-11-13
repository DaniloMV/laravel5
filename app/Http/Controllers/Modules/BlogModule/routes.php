<?php
//id
//category

Route::get('/', [
	'uses' => 'Modules\BlogModule\Module@indexCMS',
	'as' => 'blog',
	'middleware' => [],
	'where' => [],
	'domain' => NULL,
]);