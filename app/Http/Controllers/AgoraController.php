<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgoraRequest;
use App\Services\AgoraService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class AgoraController extends Controller
{
    private AgoraService $agora;

    public function __construct(AgoraService $agora)
    {
        $this->agora = $agora;
    }

    public function index(): View
    {
        return view('agora');
    }

    public function getToken(AgoraRequest $request): JsonResponse
    {
        $data = $request->validated();
        $token = $this->agora->generateToken($data['channelName'], $data['uid']);
        return response()->json([
            'token' => $token,
            'appId' => config('agora.app_id'),
        ]);
    }
}
