<?php

namespace Paksuco\Statics\Models;

use \Illuminate\Database\Eloquent\Model;

class StaticsItem extends Model
{

    protected $table = "statics_items";

    protected $fillable = [
        "category_id", "title", "slug", "excerpt", "content", "published", "order", "likes", "dislikes", "visits",
    ];

    public function getRouteKeyName()
    {
        return "slug";
    }

    public function category()
    {
        return $this->belongsTo(StaticsCategory::class, "category_id", "id");
    }
}
