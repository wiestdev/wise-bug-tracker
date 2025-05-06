<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BugReport extends Model
{
    protected $table = "bug_reports";
    protected $fillable = ["project_id", "title", "description", "status"];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
