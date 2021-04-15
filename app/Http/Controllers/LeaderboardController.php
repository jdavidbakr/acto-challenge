<?php

namespace App\Http\Controllers;

use App\Models\Hand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function index()
    {
        $data = Hand::select('name', DB::raw('count(*) as score'))
            ->where('user_won', true)
            ->groupBy('name')
            ->orderByDesc('score')
            ->get();
        return $data;
    }
}
