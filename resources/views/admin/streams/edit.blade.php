@extends('layouts.admin')

@section('title', 'Edit Stream Configuration')

@section('content')
    <div class="glass-card" style="max-width: 800px; margin: 0 auto;">
        <form action="{{ route('admin.streams.update', $stream) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                <div class="form-group">
                    <label class="form-label" for="radio_station_id">Radio Station *</label>
                    <select class="form-control" id="radio_station_id" name="radio_station_id" required>
                        <option value="">Select a station...</option>
                        @foreach ($stations as $station)
                            <option value="{{ $station->id }}" {{ old('radio_station_id', $stream->radio_station_id) == $station->id ? 'selected' : '' }}>
                                {{ $station->name }} ({{ $station->band }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="label">Stream Label *</label>
                    <input class="form-control" type="text" id="label" name="label" value="{{ old('label', $stream->label) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="stream_url">Stream URL *</label>
                <input class="form-control" type="url" id="stream_url" name="stream_url" value="{{ old('stream_url', $stream->stream_url) }}" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 24px;">
                <div class="form-group">
                    <label class="form-label" for="stream_type">Stream Type *</label>
                    <select class="form-control" id="stream_type" name="stream_type" required>
                        <option value="icecast" {{ old('stream_type', $stream->stream_type) === 'icecast' ? 'selected' : '' }}>Icecast</option>
                        <option value="shoutcast" {{ old('stream_type', $stream->stream_type) === 'shoutcast' ? 'selected' : '' }}>SHOUTcast</option>
                        <option value="hls" {{ old('stream_type', $stream->stream_type) === 'hls' ? 'selected' : '' }}>HLS (m3u8)</option>
                        <option value="raw" {{ old('stream_type', $stream->stream_type) === 'raw' ? 'selected' : '' }}>Raw Audio</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="codec">Codec *</label>
                    <input class="form-control" type="text" id="codec" name="codec" value="{{ old('codec', $stream->codec) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="bitrate">Bitrate (kbps) *</label>
                    <input class="form-control" type="number" id="bitrate" name="bitrate" value="{{ old('bitrate', $stream->bitrate) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="metadata_url">Metadata URL (Optional Icecast/SHOUTcast status JSON)</label>
                <input class="form-control" type="url" id="metadata_url" name="metadata_url" value="{{ old('metadata_url', $stream->metadata_url) }}">
            </div>

            <div style="display: flex; gap: 32px; margin-top: 32px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="checkbox-label">
                        <input class="checkbox-control" type="checkbox" name="is_default" value="1" {{ old('is_default', $stream->is_default) ? 'checked' : '' }}>
                        <span>Set as Default Stream for Station</span>
                    </label>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="checkbox-label">
                        <input class="checkbox-control" type="checkbox" name="is_active" value="1" {{ old('is_active', $stream->is_active) ? 'checked' : '' }}>
                        <span>Stream is Active</span>
                    </label>
                </div>
            </div>

            <div style="display: flex; gap: 16px; margin-top: 40px;">
                <button type="submit" class="btn btn-primary">Update Stream Config</button>
                <a href="{{ route('admin.streams.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
