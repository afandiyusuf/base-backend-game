<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameSession extends Model
{
    public function GetAllSessionByUserId($id)
    {
        return $this->where('user_id',$id)->get();
    }

    public function GetSessionLevelByUserId($id,$level_id)
    {
        return $this->where('user_id',$id)
        ->where('level_id',$level_id)->get();
    }
}
