<?php

namespace App\Http\Controllers;

use App\Services\ProjectService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProjectController extends Controller
{
    public function __construct(private readonly ProjectService $service) {}

    public function index(Request $request)
    {
        $user_id = JWTAuth::parseToken()->getPayload()->get('sub');
        return response()->json($this->service->listByUser($user_id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string',
            'description' => 'nullable|string',
        ]);
        $data['author_id'] = (int) $request->attributes->get('user_id');

        return response()->json($this->service->create($data), 201);
    }

    public function show($id)
    {
        $project = $this->service->get($id);
        abort_unless($project && $project->user_id === auth()->id(), 404);
        return response()->json($project);
    }
}