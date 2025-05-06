<?php

namespace App\Http\Controllers;

use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(private readonly ProjectService $service) {}

    public function index(Request $request)
    {
        return response()->json($this->service->listByUser(auth()->id()));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string',
            'description' => 'nullable|string',
        ]);
        $data['user_id'] = auth()->id();

        return response()->json($this->service->create($data), 201);
    }

    public function show($id)
    {
        $project = $this->service->get($id);
        abort_unless($project && $project->user_id === auth()->id(), 404);
        return response()->json($project);
    }
}