<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\LastLevel;
use App\Models\GameSession;
use Validator;
use App\ReturnData;
use App\Models\Level;
use App\Models\GameRecordScore;

class GameController extends Controller
{
    public $retData;

    public function __construct()
    {
        $this->retData = new ReturnData();
    }

    public function set_last_level(Request $request)
    {
        $last_level = new LastLevel();
        $user = new User();

        $validator = Validator::make($request->all(),[
            'last_level'=> 'required|integer'
        ]);

        if ($validator->fails()) {
            
            $this->retData->set(__('api.parameter_not_completed'),500,[]);
        }else{
            $user = $user->getUserByAccessToken($request->bearerToken());
            $data = $last_level->setLastLevelById($user->id, $request->last_level);

            $this->retData->set(__('api.success'),200,$data);
        }

        return response()->json($this->retData,$this->retData->code);
    }

    public function get_last_level(Request $request)
    {
        $data = new LastLevel();
        $user = new User();

        $user = $user->getUserByAccessToken($request->bearerToken());
        $data = $data->getLastLevelById($user->id);

        $this->retData->set(__('api.success'),200,$data);
        return response()->json($this->retData,$this->retData->code);
    }

    public function finish_game_session(Request $request)
    {
        $last_level = new LastLevel();
        $user = new User();
        
        $validator = Validator::make($request->all(),[
            'score'=> 'required|integer',
            'other_data' => 'required|string',
            'level_id' => 'required|int'
        ]);

        if ($validator->fails()) {   
            $this->retData->set(__('api.parameter_not_completed'),500,[]);
        }else{
            $level = new Level();
            $level = $level->get_level_by_id($request->level_id);
            
            if(!$level)
            {
                $this->retData->set(__('api.level_not_found'),404,[]);
            }else{
               
                

                $user = $user->getUserByAccessToken($request->bearerToken());
                
                $game_session = new GameSession();
                $game_session->user_id = $user->id;
                $game_session->level_id = $level->id;
                $game_session->score = $request->score;
                $game_session->other_data = $request->other_data;
                $game_session->save();

                $order = $level->order;
                $last_level = new LastLevel();
                $last_level = $last_level->getLastLevelById($user->id);
                
                //check if this is last level user
                if($order>$last_level->order_level)
                {
                    $last_level->setLastLevelById($user->id, $order);
                }

                //check if this is record game
                $game_record = new GameRecordScore();
                $record_score = $game_record->getRecordScore($user->id,$level->id);
                if($request->score > $record_score)
                {
                    $game_record->setRecordScore($user->id,$level->id,$request->score,$game_session->id);
                }
                //end region check record game

                $this->retData->set(__('api.success'),200,[]);
            }
        }
        return response()->json($this->retData,$this->retData->code);
    }

    public function get_all_level(Request $request)
    {
        $this->retData->set(__('api.success'),200,Level::all());
        return response()->json($this->retData,$this->retData->code);
    }

    public function get_all_history_level(Request $request)
    {
        $user = new User();
        $user = $user->getUserByAccessToken($request->bearerToken());
        
        $gameSession = new GameSession();
        $gameSession = $gameSession->GetAllSessionByUserId($user->id);
        $this->retData->set(__('api.success'),200,$gameSession);
        return response()->json($this->retData,$this->retData->code);
    }

    public function get_history_level(Request $request, $level_id)
    {
        $user = new User();
        $user = $user->getUserByAccessToken($request->bearerToken());
        
        $gameSession = new GameSession();
        $gameSession = $gameSession->GetSessionLevelByUserId($user->id,$level_id);
        $this->retData->set(__('api.success'),200,$gameSession);
        return response()->json($this->retData,$this->retData->code);
    }

    public function get_leaderboard(Request $request, $level_id, $total)
    {
        $gameRecord = new GameRecordScore();
        if($level_id == 'all')
        {
            $leaderboard_data = $gameRecord->getAllLevelLeaderboard('all',$total);
        }else{
            $leaderboard_data = $gameRecord->getLeaderboard($level_id,$total);
        }
        $this->retData->set(__('api.success'),200,$leaderboard_data);
        return response()->json($this->retData,$this->retData->code);
    }

    public function get_inbetween_leadeboard(Request $request, $level_id)
    {
        $user = new User();
        $user = $user->getUserByAccessToken($request->bearerToken());

        $gameRecord = new GameRecordScore();
        $leaderboard_data = $gameRecord->getInbetween($level_id,$user->id);
       
        $this->retData->set(__('api.success'),200,$leaderboard_data);
        return response()->json($this->retData,$this->retData->code);
    }

    public function get_current_record(Request $request,$level_id)
    {
        $user = new User();
        $user = $user->getUserByAccessToken($request->bearerToken());

        $gameRecord = new GameRecordScore();
        $data = $gameRecord->where('user_id',$user->id)->where('level_id',$level_id)->get();
        $this->retData->set(__('api.success'),200,$data);
        
        return response()->json($this->retData,$this->retData->code);
    }
}
