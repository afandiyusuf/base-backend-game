<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    public function PlayerStatistic()
    {
        return $this->hasMany('App\Models\StatisticPlayer','Statistic_id');
    }
}
