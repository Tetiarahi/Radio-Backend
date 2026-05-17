@extends('layouts.admin')

@section('title', 'Create & Dispatch Push Notification')

@section('content')
    <div class="glass-card" style="max-width: 800px; margin: 0 auto;">
        <form action="{{ route('admin.notifications.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label" for="title">Notification Title *</label>
                <input class="form-control" type="text" id="title" name="title" value="{{ old('title') }}" required placeholder="e.g. Breaking News / Live Show Starting!">
            </div>

            <div class="form-group">
                <label class="form-label" for="body">Notification Message Body *</label>
                <textarea class="form-control" id="body" name="body" rows="4" required placeholder="Enter the push notification message...">{{ old('body') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="image_url">Image URL (Optional Attachment)</label>
                <input class="form-control" type="url" id="image_url" name="image_url" value="{{ old('image_url') }}" placeholder="https://your-site.com/image.jpg">
            </div>

            <div class="form-group">
                <label class="form-label" for="target_audience">Target Audience *</label>
                <select class="form-control" id="target_audience" name="target_audience" required>
                    <option value="all" {{ old('target_audience') === 'all' ? 'selected' : '' }}>All Active Devices (Android & iOS)</option>
                    <option value="android" {{ old('target_audience') === 'android' ? 'selected' : '' }}>Android Devices Only</option>
                    <option value="ios" {{ old('target_audience') === 'ios' ? 'selected' : '' }}>iOS Devices Only</option>
                </select>
            </div>

            <div class="form-group" style="margin-top: 32px;">
                <label class="form-label">Dispatch Action *</label>
                <div style="display: flex; gap: 32px; margin-top: 12px;">
                    <label class="checkbox-label" style="cursor: pointer;">
                        <input type="radio" name="action" value="draft" {{ old('action', 'draft') === 'draft' ? 'checked' : '' }} style="width: 20px; height: 20px; accent-color: var(--brand-primary);">
                        <span>Save as Draft (Do Not Send)</span>
                    </label>

                    <label class="checkbox-label" style="cursor: pointer;">
                        <input type="radio" name="action" value="send" {{ old('action') === 'send' ? 'checked' : '' }} style="width: 20px; height: 20px; accent-color: var(--success);">
                        <span style="color: var(--success); font-weight: 600;">Send Immediately to Devices</span>
                    </label>
                </div>
            </div>

            <div style="display: flex; gap: 16px; margin-top: 40px;">
                <button type="submit" class="btn btn-primary">Submit Notification</button>
                <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
