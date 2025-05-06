<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ["title", "description", "author_id"];

    public function bugs()
    {
        return $this->hasMany(BugReport::class);
    }
}
