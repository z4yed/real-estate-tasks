@php
    $panelId = \Filament\Facades\Filament::getCurrentPanel()?->getId();
    $accounts = collect(config('settings.demo.accounts'))->where('panel', $panelId);
@endphp

@if ($accounts->isNotEmpty())
    <div class="fi-demo-login" style="margin-top:1rem; border-top:1px dashed rgba(125,125,125,.3); padding-top:1rem;">
        <p style="text-align:center; font-size:.75rem; text-transform:uppercase; letter-spacing:.08em; color:rgb(107 114 128); margin-bottom:.75rem;">
            One-click demo login
        </p>
        <div style="display:flex; flex-direction:column; gap:.5rem;">
            @foreach ($accounts as $account)
                <a href="{{ route('demo.login', $account['email']) }}">
                    <x-filament::button color="gray" class="w-full" tag="span">
                        Continue as {{ $account['label'] }}
                    </x-filament::button>
                </a>
            @endforeach
        </div>
    </div>
@endif
