<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatisticPlayer extends Model
{
    public function Statistic()
    {
        return $this->belongsTo('App\Models\Statistic','statistic_id');
    }

    public function getAllStatistic($id)
    {
        return $this->where('user_id',$id)->with('Statistic')->get();
    }

    public function setStatistic($user_id,$statistic_id,$value)
    {
        //check statistic player
        $statisticPlayer = $this->where('user_id',$user_id)->where('statistic_id',$statistic_id)->first();

        if(!$statisticPlayer)
        {
            $statisticPlayer = new StatisticPlayer();
            $statisticPlayer->statistic_id = $statistic_id;
            $statisticPlayer->user_id = $user_id;
            $statisticPlayer->value = $value;
            $statisticPlayer->save();
        }else{
            $statisticPlayer->value = $value;
            $statisticPlayer->update();
        }
    }
}
