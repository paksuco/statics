<?php

namespace Paksuco\Statics\Models;

use Illuminate\Database\Eloquent\Builder;
use \Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StaticsCategory extends Model
{
    protected $fillable = [
        "title", "slug", "description", "parent_id", "order"
    ];

    protected $table = "statics_categories";

    public function getRouteKeyName()
    {
        return "slug";
    }

    public function scopeSetParent(Builder $query, $slug)
    {
        $record = static::where("slug", $slug)->first();
        if ($record instanceof StaticsCategory) {
            $records = collect(
                DB::select(
                    "select id from (select concat('-',
                        coalesce(sp6.id, ''), '-',
                        coalesce(sp5.id, ''), '-',
                        coalesce(sp4.id, ''), '-',
                        coalesce(sp3.id, ''), '-',
                        coalesce(sp2.id, ''), '-',
                        sp1.id, '-'
                        ) as path, sp1.* from statics_categories sp1
                    left join statics_categories sp2 on sp1.parent_id = sp2.id
                    left join statics_categories sp3 on sp2.parent_id = sp3.id
                    left join statics_categories sp4 on sp3.parent_id = sp4.id
                    left join statics_categories sp5 on sp4.parent_id = sp5.id
                    left join statics_categories sp6 on sp5.parent_id = sp6.id) a
                    where path like '%-" . $record->id . "-%'"
                )
            )->pluck("id");
            return $query->whereIn("statics_categories.id", $records);
        } else {
            abort(404);
        }
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
