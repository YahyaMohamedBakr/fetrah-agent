<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\AIAgentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AIAgentController extends Controller
{
    public function chat(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'history' => 'sometimes|array',
            'history.*.role' => 'required|in:user,assistant',
            'history.*.content' => 'required|string',
        ]);

        $agent = new AIAgentService();
        $result = $agent->chat($request->message, $request->history ?? []);

        return response()->json($result);
    }
}
