<?php

/*
|--------------------------------------------------------------------------
| Authentication Defaults
|--------------------------------------------------------------------------
|
| This option controls the default authentication "guard" of the application.
| By default the guardian is enabled, but you can change this bellow.
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Guardian URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by Auth´s middleware to validate the authentication and
    | authorization of the user in the guardian when is enabled.
    |
    */

    'url' => env('GUARDIAN_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Guardian Enabled
    |--------------------------------------------------------------------------
    |
    | When guardian is enabled, all request to named routes
    | must have a authorization header will be validated in guardian.
    |
    */

    'enabled' => (bool) env('GUARDIAN', true),

    /*
    |--------------------------------------------------------------------------
    | Guardian ACL
    |--------------------------------------------------------------------------
    |
    | When guardian ACL is enabled, all request to named routes
    | will be validated the authorization to access them in guardian.
    |
    */

    'acl' => (bool) env('GUARDIAN_ACL', true),
];
