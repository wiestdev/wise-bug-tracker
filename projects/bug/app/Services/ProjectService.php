<?php


namespace App\Services;

use App\Models\Project;

class ProjectService
{
    public function create(array $data): Project
    {
        return Project::create($data);
    }

    public function listByUser(int $userId)
    {
        return Project::where('author_id', $userId)->get();
    }

    public function get(int $id): ?Project
    {
        return Project::find($id);
    }
}