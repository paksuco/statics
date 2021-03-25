<?php

namespace Paksuco\Static\Models;

use \Illuminate\Database\Eloquent\Model;

class StaticCategory extends Model
{
    protected $table = "static_categories";

    public function getRouteKeyName()
    {
        return "slug";
    }

    public function items()
    {
        return $this->hasMany(StaticItem::class, 'category_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(StaticCategory::class, "parent_id");
    }

    public function children()
    {
        return $this->hasMany(StaticCategory::class, "parent_id", "id");
    }
}
