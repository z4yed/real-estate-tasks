<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Filament Panels
    |--------------------------------------------------------------------------
    |
    | Centralised configuration for the application's Filament panels. Panel
    | providers read their path from here so URLs are not hard-coded across
    | the codebase. Role names live in the App\Enums\UserRole enum.
    |
    */

    'panels' => [
        'admin' => [
            'path' => env('ADMIN_PANEL_PATH', 'admin'),
        ],
        'agent' => [
            'path' => env('AGENT_PANEL_PATH', 'agent'),
        ],
    ],

];
