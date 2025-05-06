<?php


namespace App\Services;

use App\Models\BugReport;

class BugReportService
{
    public function create(array $data): BugReport
    {
        return BugReport::create($data);
    }

    public function listByProject(int $projectId)
    {
        return BugReport::where('project_id', $projectId)->get();
    }
}