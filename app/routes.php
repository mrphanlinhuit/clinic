<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
    return View::make('index');
});

Route::group(array(
    'prefix' => 'api/upload',
    'before' => 'api.auth',
    'after' => 'api.auth.extend'
), function () {
    Route::post('', array(
        'uses' => 'ApiUploadController@upload'
    ));
    Route::get('{id}', array(
        'before' => 'api.admin',
        'uses' => 'ApiUploadController@getById'
    ));
    Route::put('{id}', array(
        'before' => 'api.admin',
        'uses' => 'ApiUploadController@update'
    ));
    Route::delete('{hash}', array(
        'before' => 'api.admin',
        'uses' => 'ApiUploadController@delete'
    ))->where(array('hash' => '[0-9a-z]+'));
});

Route::group(array(
    'prefix' => 'api/users',
    'before' => 'api.auth',
    'after' => 'api.auth.extend'
), function () {
    Route::get('', array(
        'before' => 'api.admin',
        'uses' => 'ApiUsersController@find'
    ));
    Route::get('{id}', array(
        'uses' => 'ApiUsersController@getById'
    ))->where(array('id' => '[0-9]+'));
    Route::get('me', array(
        'uses' => 'ApiUsersController@getMe'
    ));
    Route::post('', array(
        'before' => 'api.admin',
        'uses' => 'ApiUsersController@create'
    ))->where(array('id' => '[0-9]+'));
    Route::put('{id}', array(
        'uses' => 'ApiUsersController@update'
    ));
    Route::put('me', array(
        'uses' => 'ApiUsersController@update'
    ));
    Route::delete('{id}', array(
        'before' => 'api.admin',
        'uses' => 'ApiUsersController@delete'
    ))->where(array('id' => '[0-9]+'));

});


Route::group(array(
    'prefix' => 'api/patient',
    'before' => 'api.auth',
    'after' => 'api.auth.extend'
), function () {
    Route::get('', array(
        'before' => 'api.admin',
        'uses' => 'ApiPatientController@find'
    ));
    Route::get('{id}', array(
        'uses' => 'ApiPatientController@getById'
    ))->where(array('id' => '[0-9]+'));
    Route::get('me', array(
        'uses' => 'ApiPatientController@getMe'
    ));
    Route::get('bonds', array(
        'uses' => 'ApiPatientController@getBond'
    ));
    Route::post('', array(
        'before' => 'api.admin',
        'uses' => 'ApiPatientController@create'
    ))->where(array('id' => '[0-9]+'));
    Route::put('{id}', array(
        'uses' => 'ApiPatientController@update'
    ));
    Route::put('me', array(
        'uses' => 'ApiPatientController@update'
    ));
    Route::delete('{id}', array(
        'before' => 'api.admin',
        'uses' => 'ApiPatientController@delete'
    ))->where(array('id' => '[0-9]+'));

});

Route::group(array(
    'prefix' => 'api/search',
    'before' => 'api.auth',
    'after' => 'api.auth.extend'
), function () {
    Route::get('employee', array(
        'uses' => 'ApiUsersController@employee',
    ));
    Route::get('users', array(
        'uses' => 'ApiUsersController@search',
    ));
    Route::get('users/autocomplete', array(
        'uses' => 'ApiUsersController@autocomplete',
    ));
    Route::get('users/attachments', array(
        'uses' => 'ApiUploadController@search',
    ));
});

Route::group(
    array('prefix' => 'api/auth'), function () {
        Route::post('login', array(
            'uses' => 'ApiAuthController@login'
        ));
        Route::get('logout', array(
            'before' => 'api.auth',
            'uses' => 'ApiAuthController@logout'
        ));
    }
);


Route::group(
    array(
        'prefix' => 'api/role',
        'before' => 'api.auth',
        'after' => 'api.auth.extend'
    ), function () {
        Route::get('all', array(
            'uses' => 'ApiRoleController@all'
        ));
        Route::get('search', array(
            'uses' => 'ApiRoleController@search'
        ));
        Route::post('', array(
            'before' => 'api.admin',
            'uses' => 'ApiRoleController@create'
        ));
        Route::put('{id}', array(
            'before' => 'api.admin',
            'uses' => 'ApiRoleController@update'
        ));
        Route::delete('{id}', array(
            'before' => 'api.admin',
            'uses' => 'ApiRoleController@delete'
        ))->where(array('id' => '[0-9]+'));

    }
);

Route::group(
    array(
        'prefix' => 'api/group',
        'before' => 'api.auth',
        'after' => 'api.auth.extend'
    ), function () {
        Route::get('all', array(
            'uses' => 'ApiGroupController@all'
        ));
        Route::get('search', array(
            'uses' => 'ApiGroupController@search'
        ));
        Route::post('', array(
            'before' => 'api.admin',
            'uses' => 'ApiGroupController@create'
        ));
        Route::put('{id}', array(
            'before' => 'api.admin',
            'uses' => 'ApiGroupController@update'
        ));
        Route::delete('{id}', array(
            'before' => 'api.admin',
            'uses' => 'ApiGroupController@delete'
        ))->where(array('id' => '[0-9]+'));

    }
);


Route::group(
    array(
        'prefix' => 'api/bond',
        'before' => 'api.auth',
        'after' => 'api.auth.extend'
    ), function () {
        Route::get('search', array(
            'uses' => 'ApiBondController@search'
        ));
        Route::get('{id}', array(
            'uses' => 'ApiBondController@getById'
        ))->where(array('id' => '[0-9]+'));
        Route::post('', array(
            'uses' => 'ApiBondController@create'
        ));
        Route::post('add', array(
            'uses' => 'ApiBondController@add'
        ));
        Route::put('{id}', array(
            'uses' => 'ApiBondController@update'
        ));
        Route::delete('{id}', array(
            'uses' => 'ApiBondController@delete'
        ))->where(array('id' => '[0-9]+'));
        Route::delete('remove/{id}', array(
            'uses' => 'ApiBondController@remove'
        ))->where(array('id' => '[0-9]+'));
        Route::get('bondtype', array(
            'uses' => 'ApiBondController@bondtype'
        ));
    }
);


Route::group(
    array(
        'prefix' => 'api/provider',
        'before' => 'api.auth',
        'after' => 'api.auth.extend'
    ), function () {
        Route::get('search', array(
            'uses' => 'ApiProviderController@search'
        ));
        Route::get('{id}', array(
            'uses' => 'ApiProviderController@getById'
        ))->where(array('id' => '[0-9]+'));
        Route::post('', array(
            'uses' => 'ApiProviderController@create'
        ));
        Route::put('{id}', array(
            'uses' => 'ApiProviderController@update'
        ));
        Route::delete('{id}', array(
            'uses' => 'ApiProviderController@delete'
        ))->where(array('id' => '[0-9]+'));

    }
);




Route::group(
    array(
        'prefix' => 'api/diagnostic',
        'before' => 'api.auth',
        'after' => 'api.auth.extend'
    ), function () {
        Route::get('search', array(
            'uses' => 'ApiDiagnosticController@search'
        ));
        Route::get('{id}', array(
            'uses' => 'ApiDiagnosticController@getById'
        ))->where(array('id' => '[0-9]+'));
        Route::post('', array(
            'uses' => 'ApiDiagnosticController@create'
        ));
        Route::put('{id}', array(
            'uses' => 'ApiDiagnosticController@update'
        ));
        Route::delete('{id}', array(
            'uses' => 'ApiDiagnosticController@delete'
        ))->where(array('id' => '[0-9]+'));

    }
);


Route::group(
    array(
        'prefix' => 'api/message',
        'before' => 'api.auth',
        'after' => 'api.auth.extend'
    ), function () {
        Route::get('search', array(
            'uses' => 'ApiMessageController@search'
        ));
        Route::post('', array(
            'uses' => 'ApiMessageController@create'
        ));
        Route::put('{id}', array(
            'uses' => 'ApiMessageController@update'
        ));
        Route::delete('{id}', array(
            'uses' => 'ApiMessageController@delete'
        ))->where(array('id' => '[0-9]+'));
        Route::get('{id}', array(
            'uses' => 'ApiMessageController@getById'
        ))->where(array('id' => '[0-9]+'));
    }
);



Route::group(
    array(
        'prefix' => 'api/room',
        'before' => 'api.auth',
        'after' => 'api.auth.extend'
    ), function () {
        Route::get('{id}', array(
            'uses' => 'ApiRoomController@getById'
        ))->where(array('id' => '[0-9]+'));
        Route::get('search', array(
            'uses' => 'ApiRoomController@search'
        ));
        Route::post('', array(
            'uses' => 'ApiRoomController@create'
        ));
        Route::put('{id}', array(
            'uses' => 'ApiRoomController@update'
        ));
        Route::delete('{id}', array(
            'uses' => 'ApiRoomController@delete'
        ))->where(array('id' => '[0-9]+'));

    }
);


Route::group(
    array(
        'prefix' => 'api/referer',
        'before' => 'api.auth',
        'after' => 'api.auth.extend'
    ), function () {
        Route::get('{id}', array(
            'uses' => 'ApiRefererController@getById'
        ))->where(array('id' => '[0-9]+'));
        Route::get('search', array(
            'uses' => 'ApiRefererController@search'
        ));
        Route::post('', array(
            'uses' => 'ApiRefererController@create'
        ));
        Route::put('{id}', array(
            'uses' => 'ApiRefererController@update'
        ));
        Route::delete('{id}', array(
            'uses' => 'ApiRefererController@delete'
        ))->where(array('id' => '[0-9]+'));

    }
);

Route::group(
    array(
        'prefix' => 'api/pathologie',
        'before' => 'api.auth',
        'after' => 'api.auth.extend'
    ), function () {
        Route::get('{id}', array(
            'uses' => 'ApiPathologyController@getById'
        ))->where(array('id' => '[0-9]+'));
        Route::get('search', array(
            'uses' => 'ApiPathologyController@search'
        ));
        Route::post('', array(
            'uses' => 'ApiPathologyController@create'
        ));
        Route::put('{id}', array(
            'uses' => 'ApiPathologyController@update'
        ));
        Route::delete('{id}', array(
            'uses' => 'ApiPathologyController@delete'
        ))->where(array('id' => '[0-9]+'));

    }
);


Route::group(
    array(
        'prefix' => 'api/treatment',
        'before' => 'api.auth',
        'after' => 'api.auth.extend'
    ), function () {
        Route::get('{id}', array(
            'uses' => 'ApiTreatmentController@getById'
        ))->where(array('id' => '[0-9]+'));
        Route::get('search', array(
            'uses' => 'ApiTreatmentController@search'
        ));
        Route::post('', array(
            'uses' => 'ApiTreatmentController@create'
        ));
        Route::put('{id}', array(
            'uses' => 'ApiTreatmentController@update'
        ));
        Route::delete('{id}', array(
            'uses' => 'ApiTreatmentController@delete'
        ))->where(array('id' => '[0-9]+'));

    }
);

Route::group(
    array(
        'prefix' => 'api/prescription',
        'before' => 'api.auth',
        'after' => 'api.auth.extend'
    ), function () {
        Route::get('{id}', array(
            'uses' => 'ApiPrescriptionController@getById'
        ))->where(array('id' => '[0-9]+'));
        Route::get('search', array(
            'uses' => 'ApiPrescriptionController@search'
        ));
        Route::post('', array(
            'uses' => 'ApiPrescriptionController@create'
        ));
        Route::put('{id}', array(
            'uses' => 'ApiPrescriptionController@update'
        ));
        Route::delete('{id}', array(
            'uses' => 'ApiPrescriptionController@delete'
        ))->where(array('id' => '[0-9]+'));

    }
);

Route::group(
    array(
        'prefix' => 'api/review',
        'before' => 'api.auth',
        'after' => 'api.auth.extend'
    ), function () {
        Route::get('{id}', array(
            'uses' => 'ApiDiagnosticReviewController@getById'
        ))->where(array('id' => '[0-9]+'));
        Route::get('search', array(
            'uses' => 'ApiDiagnosticReviewController@search'
        ));
        Route::post('', array(
            'uses' => 'ApiDiagnosticReviewController@create'
        ));
        Route::put('{id}', array(
            'uses' => 'ApiDiagnosticReviewController@update'
        ));
        Route::delete('{id}', array(
            'uses' => 'ApiDiagnosticReviewController@delete'
        ))->where(array('id' => '[0-9]+'));

    }
);

Route::group(
    array(
        'prefix' => 'api/session',
        'before' => 'api.auth',
        'after' => 'api.auth.extend'
    ), function () {
        Route::get('{id}', array(
            'uses' => 'ApiSessionController@getById'
        ))->where(array('id' => '[0-9]+'));
        Route::get('search', array(
            'uses' => 'ApiSessionController@search'
        ));
        Route::post('', array(
            'uses' => 'ApiSessionController@create'
        ));
        Route::put('{id}', array(
            'uses' => 'ApiSessionController@update'
        ));
        Route::post('status', array(
            'uses' => 'ApiSessionController@setStatus'
        ));        
        Route::delete('{id}', array(
            'uses' => 'ApiSessionController@delete'
        ))->where(array('id' => '[0-9]+'));

    }
);

Route::group(
    array(
        'prefix' => 'api/timeline',
        'before' => 'api.auth',
        'after' => 'api.auth.extend'
    ), function () {
        Route::get('{id}', array(
            'uses' => 'ApiTimelineController@getById'
        ))->where(array('id' => '[0-9]+'));
        Route::get('search', array(
            'uses' => 'ApiTimelineController@search'
        ));
        Route::post('', array(
            'uses' => 'ApiTimelineController@create'
        ));
        Route::put('{id}', array(
            'uses' => 'ApiTimelineController@update'
        ));
        Route::delete('{id}', array(
            'uses' => 'ApiTimelineController@delete'
        ))->where(array('id' => '[0-9]+'));
    }
);

Route::group(
    array(
        'prefix' => 'api/invoice',
        'before' => 'api.auth',
        'after' => 'api.auth.extend'
    ), function () {
        Route::get('{id}', array(
            'uses' => 'ApiInvoiceController@getById'
        ))->where(array('id' => '[0-9]+'));
        Route::get('search', array(
            'uses' => 'ApiInvoiceController@search'
        ));
        Route::post('', array(
            'uses' => 'ApiInvoiceController@create'
        ));
        Route::post('send', array(
            'uses' => 'ApiInvoiceController@send'
        ));
        Route::post('amendments', array(
            'uses' => 'ApiInvoiceController@amendmentsBill'
        ));
        Route::get('sent', array(
            'uses' => 'ApiInvoiceController@invoicesSent'
        ));
        Route::get('provider', array(
            'uses' => 'ApiInvoiceController@provider'
        ));
        Route::get('amendments', array(
            'uses' => 'ApiInvoiceController@amendments'
        ));
        Route::get('receive', array(
            'uses' => 'ApiInvoiceController@invoicesReceive'
        ));
        Route::put('{id}', array(
            'uses' => 'ApiInvoiceController@update'
        ))->where(array('id' => '[0-9]+'));
        Route::put('/amendments/{id}', array(
            'uses' => 'ApiInvoiceController@invoicesUpdate'
        ))->where(array('id' => '[0-9]+'));
        Route::delete('{id}', array(
            'uses' => 'ApiInvoiceController@delete'
        ))->where(array('id' => '[0-9]+'));
        Route::delete('/sent/{id}', array(
            'uses' => 'ApiInvoiceController@invoicesDelete'
        ))->where(array('id' => '[0-9]+'));
    }
);


Route::group(
    array(
        'prefix' => 'api/news',
        'before' => 'api.auth',
        'after' => 'api.auth.extend'
    ), function () {
        Route::get('{id}', array(
            'uses' => 'ApiNewsController@getById'
        ))->where(array('id' => '[0-9]+'));
        Route::get('search', array(
            'uses' => 'ApiNewsController@search'
        ));
        Route::post('', array(
            'uses' => 'ApiNewsController@create'
        ));
        Route::put('{id}', array(
            'uses' => 'ApiNewsController@update'
        ));
        Route::delete('{id}', array(
            'uses' => 'ApiNewsController@delete'
        ))->where(array('id' => '[0-9]+'));

    }
);


Route::group(
    array(
        'prefix' => 'api/movements',
        'before' => 'api.auth',
        'after' => 'api.auth.extend'
    ), function () {
        Route::get('{id}', array(
            'uses' => 'ApiMovementsController@getById'
        ))->where(array('id' => '[0-9]+'));
        Route::get('search', array(
            'uses' => 'ApiMovementsController@search'
        ));
        Route::post('', array(
            'uses' => 'ApiMovementsController@create'
        ));
        Route::post('spending', array(
            'uses' => 'ApiMovementsController@spending'
        ));
        Route::post('devolution', array(
            'uses' => 'ApiMovementsController@devolution'
        ));
        Route::put('{id}', array(
            'uses' => 'ApiMovementsController@update'
        ));
        Route::delete('{id}', array(
            'uses' => 'ApiMovementsController@delete'
        ))->where(array('id' => '[0-9]+'));
    }
);

Route::group(
    array(
        'prefix' => 'api/tags',
        'before' => 'api.auth',
        'after' => 'api.auth.extend'
    ), function () {
        Route::get('{id}', array(
            'uses' => 'ApiTagsController@getById'
        ))->where(array('id' => '[0-9]+'));
        Route::get('search', array(
            'uses' => 'ApiTagsController@search'
        ));
        Route::post('', array(
            'uses' => 'ApiTagsController@create'
        ));
        Route::put('{id}', array(
            'uses' => 'ApiTagsController@update'
        ));
        Route::delete('{id}', array(
            'uses' => 'ApiTagsController@delete'
        ))->where(array('id' => '[0-9]+'));

    }
);