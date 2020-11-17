<?php

namespace App\Http\Controllers;

use App\Repositories\MatchingRepositoryInterface;
use Illuminate\Http\Request;

class MatchingController extends Controller
{
    private $matching;

    public function __construct(MatchingRepositoryInterface $matching )
    {
        $this->matching = $matching;
        
    }
    
    public function indexmatchingActivityAndAdimssion(){
        $activity = $this->matching->getAllmatchingActivityAndAdimssion();
        return response()->json($activity, 200);

    }
}
