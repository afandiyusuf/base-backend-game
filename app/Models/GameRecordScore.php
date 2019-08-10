<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class GameRecordScore extends Model
{
    public function getRecordScore($user_id,$level_id)
    {
        $data =  $this->where('user_id',$user_id)
        ->where('level_id',$level_id)->first();
        if(!$data)
        {
            return 0;
        }else{
            return $data->score;
        }
    }
    public function Users()
    {
        return $this->hasOne('App\User','id','user_id');
    }
    public function setRecordScore($user_id, $level_id, $score,$session_id)
    {
        $data =  $this->where('user_id',$user_id)
        ->where('level_id',$level_id)->first();
        if(!$data)
        {
            $session = new GameRecordScore();
            $session->user_id = $user_id;
            $session->level_id = $level_id;
            $session->score = $score;
            $session->session_id = $session_id;
            $session->save();
        }else{
            $data->score = $score;
            $data->save();
        }
    }

    public function getLeaderboard($lvl_id,$total = 'all')
    {
        if($total != 'all'){
            $data = $this
            ->where('level_id',$lvl_id)
            ->limit($total)
            ->orderBy('score','desc')
            ->with(array('Users'=>function($query){
                $query->select('id','Name','Gender');
            }))->with('session')
            ->get();
        }else{
            $data = $this
            ->where('level_id',$lvl_id)
            ->orderBy('score','desc')
            ->with(array('Users'=>function($query){
                $query->select('id','Name','Gender');
            }))->with('session')
            ->get();
        }
        for($i=0;$i<count($data);$i++)
        {
            $data[$i]->position = ($i+1);
        }
        return $data;
    }

    public function getAllLevelLeaderboard($total ='all')
    {
        if($total != 'all'){
            $data = $this
            ->limit($total)
            ->orderBy('score','desc')
            ->with(array('Users'=>function($query){
                $query->select('id','Name','Gender');
            }))->with('session')
            ->get();
        }else{
            $data = $this
            ->orderBy('score','desc')
            ->with(array('Users'=>function($query){
                $query->select('id','Name','Gender');
            }))->with('session')
            ->get();
        }
        for($i=0;$i<count($data);$i++)
        {
            $data[$i]->position = ($i+1);
        }
        return $data;
    }

    public function session(){
        return $this->hasOne('App\Models\GameSession','id','session_id');
    }

    public function getInbetween($level_id,$user_id)
    {
        $data = $this->getLeaderboard($level_id);
        $inbetweenData = [];
        $index = 0;
        $position = 0;
        for($i =0; $i<count($data); $i++)
        {

            if($data[$i]->user_id == $user_id)
            {
                if($i != 0)
                {
                    $inbetweenData[0] = $data[$i-1];
                    $index++;
                }

                $inbetweenData[$index] = $data[$i];
                $position = $i+1;
                

                if($i != count($data) -1)
                {
                    $inbetweenData[$index+1] = $data[$i+1];
                }
            }
        }
        $data = 
        [
            "leaderboard_data" => $inbetweenData,
            "position" => $position,
            "total_data" => count($data)
        ];

        return $data;
    }
}
