<?php

namespace App\Http\Controllers;

use App\Services\BugReportService;
use Illuminate\Http\Request;

class BugReportController extends Controller
{
    public function __construct(private readonly BugReportService $service) {}

    public function index($projectId)
    {
        return response()->json($this->service->listByProject($projectId));
    }

    public function store(Request $request, $projectId)
    {
        $data = $request->validate([
            'title'       => 'required|string',
            'description' => 'nullable|string',
        ]);
        $data['project_id'] = $projectId;

        return response()->json($this->service->create($data), 201);
    }
}
