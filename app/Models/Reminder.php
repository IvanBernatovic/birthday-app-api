<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reminder extends Model
{
    use SoftDeletes;

    protected $fillable = ['before_amount', 'before_unit', 'remind_at'];

    protected $touches = ['remindable'];

    public function remindable()
    {
        return $this->morphTo();
    }
}
