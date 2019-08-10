<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    public function get_level_by_id($id)
    {
        return $this->where('id',$id)->first();
    }

    public function get_all_levels()
    {
        return $this->all();
    }
}
