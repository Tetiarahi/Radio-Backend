<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{
    /**
     * GET /api/v1/settings
     * Returns all app settings as a flat key=>value object.
     * Sensitive keys (starting with _) are excluded.
     */
    public function index(): JsonResponse
    {
        $settings = AppSetting::all()
            ->reject(fn ($s) => str_starts_with($s->key, '_'))
            ->mapWithKeys(fn ($s) => [$s->key => $s->typed_value]);

        return response()->json([
            'success' => true,
            'data' => $settings,
        ]);
    }
}
