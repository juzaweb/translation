<?php

use Juzaweb\Translation\Http\Controllers\ModuleController;
use Juzaweb\Translation\Http\Controllers\TranslationController;

Route::group(
    ['prefix' => 'translations'],
    function () {
        Route::get('/', [TranslationController::class, 'index'])->name('admin.translations.index');
        Route::get('/get-data', [TranslationController::class, 'getDataTable'])->name('admin.translations.get-data');
    }
);

Route::group(
    ['prefix' => 'translations/{type}'],
    function () {
        Route::get('/', [ModuleController::class, 'index'])->name('admin.translations.type');
        Route::get('/get-data', 'ModuleController@getDataTable')->name('admin.translations.type.get-data');
        Route::post('/add', 'ModuleController@add')->name('admin.translations.type.add');
    }
);

Route::group(
    ['prefix' => 'translations/{type}/{locale}'],
    function () {
        Route::get('/', 'LocaleController@index')->name('admin.translations.locale');
        Route::post('/', 'LocaleController@save')->name('admin.translations.locale.save');
        Route::get('/get-data', 'LocaleController@getDataTable')->name('admin.translations.locale.get-data');
    }
);
