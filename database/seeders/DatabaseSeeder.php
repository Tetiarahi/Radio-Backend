<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use App\Models\RadioStation;
use App\Models\StreamConfig;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin User ────────────────────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'admin@radioapp.com'],
            [
                'name'     => 'Admin',
                'email'    => 'admin@radioapp.com',
                'password' => Hash::make('password'),
            ]
        );

        // ── Radio Stations ─────────────────────────────────────────────────────
        $fm = RadioStation::updateOrCreate(['slug' => 'radio-fm'], [
            'name'        => 'Radio FM',
            'tagline'     => 'Your #1 FM Station',
            'description' => 'The best FM radio station streaming 24/7.',
            'frequency'   => '101.5 FM',
            'band'        => 'FM',
            'genre'       => 'Pop',
            'language'    => 'English',
            'country'     => 'Pacific',
            'is_active'   => true,
            'sort_order'  => 1,
        ]);

        $am = RadioStation::updateOrCreate(['slug' => 'radio-am'], [
            'name'        => 'Radio AM',
            'tagline'     => 'Classic AM Radio',
            'description' => 'Talk radio, news, and sports 24/7.',
            'frequency'   => '1080 AM',
            'band'        => 'AM',
            'genre'       => 'Talk',
            'language'    => 'English',
            'country'     => 'Pacific',
            'is_active'   => true,
            'sort_order'  => 2,
        ]);

        // ── Stream Configs ─────────────────────────────────────────────────────
        StreamConfig::updateOrCreate(['stream_url' => 'https://stream.your-radio.com/fm'], [
            'radio_station_id' => $fm->id,
            'label'            => '128kbps MP3',
            'stream_type'      => 'icecast',
            'codec'            => 'mp3',
            'bitrate'          => 128,
            'is_https'         => true,
            'is_default'       => true,
            'is_active'        => true,
            'metadata_url'     => 'https://stream.your-radio.com/status-json.xsl',
        ]);

        StreamConfig::updateOrCreate(['stream_url' => 'https://stream.your-radio.com/am'], [
            'radio_station_id' => $am->id,
            'label'            => '64kbps MP3',
            'stream_type'      => 'icecast',
            'codec'            => 'mp3',
            'bitrate'          => 64,
            'is_https'         => true,
            'is_default'       => true,
            'is_active'        => true,
            'metadata_url'     => 'https://stream.your-radio.com/status-json.xsl',
        ]);

        // ── App Settings ───────────────────────────────────────────────────────
        $settings = [
            // General
            ['key' => 'app_name',           'value' => 'Radio App',          'type' => 'string',  'group' => 'general', 'label' => 'App Name'],
            ['key' => 'app_tagline',         'value' => 'Stream. Listen. Enjoy.', 'type' => 'string', 'group' => 'general', 'label' => 'App Tagline'],
            ['key' => 'wp_site_url',         'value' => 'https://your-wordpress-site.com', 'type' => 'url', 'group' => 'general', 'label' => 'WordPress Site URL'],
            ['key' => 'news_per_page',       'value' => '20',                 'type' => 'integer', 'group' => 'general', 'label' => 'News Per Page'],

            // Appearance
            ['key' => 'primary_color',       'value' => '#6C63FF',            'type' => 'color',   'group' => 'appearance', 'label' => 'Primary Color'],
            ['key' => 'secondary_color',     'value' => '#FF6584',            'type' => 'color',   'group' => 'appearance', 'label' => 'Secondary Color'],
            ['key' => 'dark_mode_default',   'value' => '1',                  'type' => 'boolean', 'group' => 'appearance', 'label' => 'Default Dark Mode'],

            // Social
            ['key' => 'facebook_url',        'value' => 'https://facebook.com/yourpage',   'type' => 'url', 'group' => 'social', 'label' => 'Facebook URL'],
            ['key' => 'instagram_url',       'value' => 'https://instagram.com/yourpage',  'type' => 'url', 'group' => 'social', 'label' => 'Instagram URL'],
            ['key' => 'twitter_url',         'value' => 'https://twitter.com/yourpage',    'type' => 'url', 'group' => 'social', 'label' => 'Twitter/X URL'],
            ['key' => 'youtube_url',         'value' => '',                               'type' => 'url', 'group' => 'social', 'label' => 'YouTube URL'],

            // Contact
            ['key' => 'contact_email',       'value' => 'contact@yourradio.com',           'type' => 'string', 'group' => 'contact', 'label' => 'Contact Email'],
            ['key' => 'contact_phone',       'value' => '+1 234 567 890',                  'type' => 'string', 'group' => 'contact', 'label' => 'Contact Phone'],
            ['key' => 'contact_address',     'value' => '123 Radio Street, City, Country', 'type' => 'string', 'group' => 'contact', 'label' => 'Address'],
            ['key' => 'contact_whatsapp',    'value' => '+1234567890',                     'type' => 'string', 'group' => 'contact', 'label' => 'WhatsApp Number'],
        ];

        foreach ($settings as $setting) {
            AppSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
