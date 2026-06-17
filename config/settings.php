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

    /*
    |--------------------------------------------------------------------------
    | Demo Mode
    |--------------------------------------------------------------------------
    |
    | When enabled, the welcome page and panel login screens expose one-click
    | login buttons for the seeded demo accounts. Disable in real production
    | environments by setting DEMO_MODE=false.
    |
    */

    'demo' => [
        'enabled' => env('DEMO_MODE', true),
        'accounts' => [
            ['label' => 'Admin', 'email' => 'admin@example.com', 'panel' => 'admin'],
            ['label' => 'Alice (Agent)', 'email' => 'agent1@example.com', 'panel' => 'agent'],
            ['label' => 'Bob (Agent)', 'email' => 'agent2@example.com', 'panel' => 'agent'],
            ['label' => 'Carol (Agent)', 'email' => 'agent3@example.com', 'panel' => 'agent'],
        ],
    ],

];
