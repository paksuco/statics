<?php

namespace Paksuco\Static\Models;

use \Illuminate\Database\Eloquent\Model;

class StaticItem extends Model
{

    protected $table = "static_items";

    public function getRouteKeyName()
    {
        return "slug";
    }

    public function category()
    {
        return $this->belongsTo(StaticCategory::class, "category_id", "id");
    }
}
