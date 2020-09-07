<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ActivityFile extends Model
{
    protected $table = 'activity_file';

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }
}
