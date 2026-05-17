@extends('layouts.admin')

@section('title', 'Global Application Settings')

@section('content')
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div style="display: flex; flex-direction: column; gap: 40px; max-width: 900px;">
            @foreach ($settings as $group => $items)
                <div class="glass-card">
                    <h2 style="font-size: 20px; margin-bottom: 24px; text-transform: capitalize; border-bottom: 1px solid var(--border-glass); padding-bottom: 12px; color: var(--brand-primary);">
                        {{ $group }} Settings
                    </h2>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                        @foreach ($items as $setting)
                            <div class="form-group" style="grid-column: {{ $setting->type === 'textarea' || $setting->key === 'wp_site_url' ? 'span 2' : 'span 1' }};">
                                <label class="form-label" for="{{ $setting->key }}">{{ $setting->label }}</label>
                                
                                @if ($setting->description)
                                    <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 8px;">{{ $setting->description }}</div>
                                @endif

                                @if ($setting->type === 'boolean')
                                    <label class="checkbox-label" style="margin-top: 8px;">
                                        <input class="checkbox-control" type="checkbox" id="{{ $setting->key }}" name="{{ $setting->key }}" value="1" {{ $setting->value ? 'checked' : '' }}>
                                        <span>Enable {{ $setting->label }}</span>
                                    </label>
                                @elseif ($setting->type === 'color')
                                    <div style="display: flex; align-items: center; gap: 16px;">
                                        <input type="color" id="{{ $setting->key }}" name="{{ $setting->key }}" value="{{ $setting->value }}" style="width: 50px; height: 40px; background: transparent; border: 1px solid var(--border-glass); border-radius: 8px; cursor: pointer;">
                                        <input class="form-control" type="text" value="{{ $setting->value }}" style="width: 140px;" onchange="document.getElementById('{{ $setting->key }}').value = this.value">
                                    </div>
                                @elseif ($setting->type === 'integer')
                                    <input class="form-control" type="number" id="{{ $setting->key }}" name="{{ $setting->key }}" value="{{ $setting->value }}">
                                @elseif ($setting->type === 'url')
                                    <input class="form-control" type="url" id="{{ $setting->key }}" name="{{ $setting->key }}" value="{{ $setting->value }}" placeholder="https://...">
                                @else
                                    <input class="form-control" type="text" id="{{ $setting->key }}" name="{{ $setting->key }}" value="{{ $setting->value }}">
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="glass-card" style="display: flex; justify-content: flex-end; gap: 16px; position: sticky; bottom: 20px; z-index: 20; background: rgba(15, 15, 26, 0.85);">
                <button type="submit" class="btn btn-primary" style="padding: 14px 32px; font-size: 16px;">Save All Settings</button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary" style="padding: 14px 24px; font-size: 16px;">Cancel</a>
            </div>
        </div>
    </form>
@endsection
