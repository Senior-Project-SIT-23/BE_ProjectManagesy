<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table= 'activity';
    protected $fillable = ['activity_name', 'activity_major'];
    public function activity_file()
    {
        return $this->hasMany(ActivityFile::class,'activity_id');

    }
}
