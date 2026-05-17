<?php

namespace App\Http\Controllers\AdminWeb;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Display the application settings grouped by category.
     */
    public function index(): View
    {
        $settings = AppSetting::all()->groupBy('group');

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update the application settings in storage.
     */
    public function update(Request $request): RedirectResponse
    {
        $data = $request->except(['_token', '_method']);

        foreach ($data as $key => $value) {
            // Check if setting exists to get its type
            $setting = AppSetting::where('key', $key)->first();
            if ($setting && $setting->type === 'boolean') {
                $value = $value ? '1' : '0';
            }
            AppSetting::set($key, $value ?? '');
        }

        // Handle unchecked boolean checkboxes
        $booleans = AppSetting::where('type', 'boolean')->get();
        foreach ($booleans as $bool) {
            if (! isset($data[$bool->key])) {
                AppSetting::set($bool->key, '0');
            }
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Application settings updated successfully.');
    }
}
