<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminSettingsController extends Controller
{
    public function index(): JsonResponse
    {
        $settings = AppSetting::orderBy('group')->orderBy('key')->get();
        return response()->json(['success' => true, 'data' => $settings]);
    }

    /**
     * PUT /api/v1/admin/settings
     * Accepts an array of {key, value} pairs to upsert.
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'settings'         => ['required', 'array'],
            'settings.*.key'   => ['required', 'string'],
            'settings.*.value' => ['nullable'],
        ]);

        foreach ($request->settings as $item) {
            AppSetting::where('key', $item['key'])->update(['value' => $item['value']]);
        }

        return response()->json(['success' => true, 'message' => 'Settings updated.']);
    }
}
