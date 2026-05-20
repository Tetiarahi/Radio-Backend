@extends('layouts.admin')

@section('title', 'Edit Radio Station: ' . $station->name)

@section('content')
    <div class="glass-card" style="max-width: 800px; margin: 0 auto;">
        <form action="{{ route('admin.stations.update', $station) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                <div class="form-group">
                    <label class="form-label" for="name">Station Name *</label>
                    <input class="form-control" type="text" id="name" name="name" value="{{ old('name', $station->name) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="band">Band *</label>
                    <select class="form-control" id="band" name="band" required>
                        <option value="FM" {{ old('band', $station->band) === 'FM' ? 'selected' : '' }}>FM</option>
                        <option value="AM" {{ old('band', $station->band) === 'AM' ? 'selected' : '' }}>AM</option>
                        <option value="ONLINE" {{ old('band', $station->band) === 'ONLINE' ? 'selected' : '' }}>Online Stream</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="tagline">Tagline</label>
                <input class="form-control" type="text" id="tagline" name="tagline" value="{{ old('tagline', $station->tagline) }}">
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $station->description) }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                <div class="form-group">
                    <label class="form-label" for="frequency">Frequency</label>
                    <input class="form-control" type="text" id="frequency" name="frequency" value="{{ old('frequency', $station->frequency) }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="genre">Genre</label>
                    <input class="form-control" type="text" id="genre" name="genre" value="{{ old('genre', $station->genre) }}">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 24px;">
                <div class="form-group">
                    <label class="form-label" for="language">Language</label>
                    <input class="form-control" type="text" id="language" name="language" value="{{ old('language', $station->language) }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="country">Country</label>
                    <input class="form-control" type="text" id="country" name="country" value="{{ old('country', $station->country) }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="sort_order">Sort Order</label>
                    <input class="form-control" type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $station->sort_order) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="logo">Station Logo (Image)</label>
                @if ($station->logo_url)
                    <div style="margin-bottom: 16px; display: flex; align-items: center; gap: 16px;">
                        <img src="{{ $station->logo_url }}" alt="{{ $station->name }}" style="width: 64px; height: 64px; border-radius: 14px; object-fit: cover;">
                        <span style="color: var(--text-secondary); font-size: 13px;">Current Logo</span>
                    </div>
                @endif
                <input class="form-control" type="file" id="logo" name="logo" accept="image/*" style="padding: 10px;">
                <div style="font-size: 12px; color: var(--text-muted); margin-top: 6px;">Leave blank to keep current logo.</div>
            </div>

            <div class="form-group" style="margin-top: 32px;">
                <label class="checkbox-label">
                    <input class="checkbox-control" type="checkbox" name="is_active" value="1" {{ old('is_active', $station->is_active) ? 'checked' : '' }}>
                    <span>Station is Active (Visible in Mobile App)</span>
                </label>
            </div>

            <div style="display: flex; gap: 16px; margin-top: 40px;">
                <button type="submit" class="btn btn-primary">Update Station</button>
                <a href="{{ route('admin.stations.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
