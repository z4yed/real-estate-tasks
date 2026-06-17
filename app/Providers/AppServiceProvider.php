<?php

namespace App\Providers;

use Filament\Actions\Action;
use Filament\Support\Facades\FilamentView;
use Filament\Support\Icons\Heroicon;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaultActionIcons();
        $this->configureDemoLoginButtons();
    }

    private function configureDemoLoginButtons(): void
    {
        if (! config('settings.demo.enabled')) {
            return;
        }

        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
            fn (): string => view('filament.demo-login-buttons')->render(),
        );
    }

    private function configureDefaultActionIcons(): void
    {
        Action::configureUsing(function (Action $action): void {
            $icon = match ($action->getName()) {
                'create', 'createAnother' => Heroicon::Plus,
                'save' => Heroicon::Check,
                'cancel' => Heroicon::XMark,
                'edit' => Heroicon::PencilSquare,
                'view' => Heroicon::Eye,
                'delete', 'forceDelete' => Heroicon::Trash,
                'restore' => Heroicon::ArrowUturnLeft,
                default => null,
            };

            if ($icon !== null) {
                $action->icon($icon);
            }
        });
    }
}
