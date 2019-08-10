<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LastLevel extends Model
{
    public function getLastLevelById($id)
    {
        $last_level = $this->where('user_id',$id)->first();
        
        if(!$last_level){
            $last_level = new LastLevel();
            $last_level->user_id = $id;
            $last_level->order_level = 0;
            $last_level->save();
        }

        return $last_level;
    }

    public function setLastLevelById($id, $last_level_value)
    {
        $last_level = $this->where('user_id',$id)->first();

        if(!$last_level){
            $last_level = $this;
            $last_level->order_level = $last_level_value;
            $last_level->user_id = $id;
            $last_level->save();
        }else{
            $last_level->order_level = $last_level_value;
            $last_level->update();
        }

        return $last_level;
    }
}
