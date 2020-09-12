<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AdmissionFile extends Model
{
    protected $table = 'admission_file';

    public function admission()
    {
        return $this->belongsTo(Activity::class, 'admission_id');
    }
}
