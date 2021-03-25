<?php

namespace Paksuco\Statics\Models;

use \Illuminate\Database\Eloquent\Model;

class StaticsCategory extends Model
{
    protected $table = "static_categories";

    public function getRouteKeyName()
    {
        return "slug";
    }

    public function items()
    {
        return $this->hasMany(StaticsItem::class, 'category_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(StaticsCategory::class, "parent_id");
    }

    public function children()
    {
        return $this->hasMany(StaticsCategory::class, "parent_id", "id");
    }
}
