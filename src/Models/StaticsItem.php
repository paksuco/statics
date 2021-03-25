<?php

namespace Paksuco\Statics\Models;

use \Illuminate\Database\Eloquent\Model;

class StaticsItem extends Model
{

    protected $table = "static_items";

    public function getRouteKeyName()
    {
        return "slug";
    }

    public function category()
    {
        return $this->belongsTo(StaticsCategory::class, "category_id", "id");
    }
}
