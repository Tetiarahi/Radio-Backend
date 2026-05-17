@extends('layouts.admin')

@section('title', 'Add New Radio Station')

@section('content')
    <div class="glass-card" style="max-width: 800px; margin: 0 auto;">
        <form action="{{ route('admin.stations.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                <div class="form-group">
                    <label class="form-label" for="name">Station Name *</label>
                    <input class="form-control" type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="e.g. Radio FM">
                </div>

                <div class="form-group">
                    <label class="form-label" for="band">Band *</label>
                    <select class="form-control" id="band" name="band" required>
                        <option value="FM" {{ old('band') === 'FM' ? 'selected' : '' }}>FM</option>
                        <option value="AM" {{ old('band') === 'AM' ? 'selected' : '' }}>AM</option>
                        <option value="ONLINE" {{ old('band') === 'ONLINE' ? 'selected' : '' }}>Online Stream</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="tagline">Tagline</label>
                <input class="form-control" type="text" id="tagline" name="tagline" value="{{ old('tagline') }}" placeholder="e.g. Your #1 Hit Music Station">
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Detailed station description...">{{ old('description') }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                <div class="form-group">
                    <label class="form-label" for="frequency">Frequency</label>
                    <input class="form-control" type="text" id="frequency" name="frequency" value="{{ old('frequency') }}" placeholder="e.g. 101.5 FM">
                </div>

                <div class="form-group">
                    <label class="form-label" for="genre">Genre</label>
                    <input class="form-control" type="text" id="genre" name="genre" value="{{ old('genre') }}" placeholder="e.g. Pop, Rock, News">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 24px;">
                <div class="form-group">
                    <label class="form-label" for="language">Language</label>
                    <input class="form-control" type="text" id="language" name="language" value="{{ old('language', 'English') }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="country">Country</label>
                    <input class="form-control" type="text" id="country" name="country" value="{{ old('country', 'Pacific') }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="sort_order">Sort Order</label>
                    <input class="form-control" type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="logo">Station Logo (Image)</label>
                <input class="form-control" type="file" id="logo" name="logo" accept="image/*" style="padding: 10px;">
            </div>

            <div class="form-group" style="margin-top: 32px;">
                <label class="checkbox-label">
                    <input class="checkbox-control" type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <span>Station is Active (Visible in Mobile App)</span>
                </label>
            </div>

            <div style="display: flex; gap: 16px; margin-top: 40px;">
                <button type="submit" class="btn btn-primary">Save Station</button>
                <a href="{{ route('admin.stations.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
