<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gift extends Model
{
    use SoftDeletes;

    protected $fillable = ['body'];

    protected $touches = ['birthday'];

    public function birthday()
    {
        return $this->belongsTo(Birthday::class);
    }
}
