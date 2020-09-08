<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ActivityRepositoryInterface;

class ActivityManagementController extends Controller
{
    private $activity;

    public function __construct(ActivityRepositoryInterface $activity)

    {
        $this->activity = $activity;
    }

    public function storeActivity(Request $request)
    {
        $data = $request->all();
        $this->activity->createActivity($data);
        
        return response()->json('สำเร็จ', 200);
    }

    public function indexActivity()
    {
        $activity = $this->activity->getAllActivity();
        return response()->json($activity, 200);
    }
}
