<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ReturnData;
use Validator;
use App\User;
use App\Models\Statistic;
use App\Models\StatisticPlayer;

class StatisticController extends Controller
{
    public function __construct()
    {
        $this->retData = new ReturnData();
    }

    public function getAllStatistic(Request $request)
    {
        $user = new User();
        $StatisticPlayer = new StatisticPlayer();
        $user = $user->getUserByAccessToken($request->bearerToken());
        $returnArray = [
            "all_statistics" => Statistic::all(),
            "player_statistics" => $StatisticPlayer->getAllStatistic($user->id),
        ];

        $this->retData->set(__('api.success'),200,$returnArray);
        return response()->json($this->retData,$this->retData->code);
    }

    public function setStatistic(Request $request)
    {
        $user = new User();
        $StatisticPlayer = new StatisticPlayer();
        $user = $user->getUserByAccessToken($request->bearerToken());
        $StatisticPlayer->setStatistic($user->id,$request->statistic_id,$request->value);
        
        return $this->getAllStatistic($request);
    }
}
