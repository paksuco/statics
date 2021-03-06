<?php

namespace Paksuco\Statics\Tables;

use Illuminate\Support\Str;
use Paksuco\Statics\Models\StaticsCategory;
use Paksuco\Statics\Models\StaticsItem;

class StaticsItemsTable extends \Paksuco\Table\Contracts\TableSettings
{
    public $model = StaticsItem::class;
    public $relations = ["category"];
    public $queryable = true;
    public $sortable = true;
    public $pageable = true;
    public $perPages = [10, 25, 50, 100];
    public $perPage = 25;

    public static $category = null;

    public $fields = [
        [
            "name" => "id",
            "type" => "field",
            "format" => "string",
            "sortable" => true,
            "queryable" => false,
            "filterable" => false,
        ],
        [
            "name" => "category",
            "type" => "callback",
            "format" => StaticsItemsTable::class . "::getCategoryTitle",
            "sortable" => true,
            "queryable" => true,
            "filterable" => true,
        ],
        [
            "name" => 'title',
            "type" => "field",
            "class" => "",
            "format" => "string",
            "sortable" => true,
            "queryable" => true,
            "filterable" => false,
        ],
        [
            "name" => "excerpt",
            "type" => "callback",
            "format" => StaticsItemsTable::class . "::getExcerpt",
            "sortable" => true,
            "queryable" => true,
            "filterable" => false,
        ],
        [
            "name" => 'order',
            "type" => "field",
            "format" => "string",
            "sortable" => true,
            "queryable" => false,
            "filterable" => false,
        ],
        [
            "name" => "likes",
            "type" => "field",
            "format" => "string",
            "sortable" => true,
            "queryable" => false,
            "filterable" => false,
        ],
        [
            "name" => "dislikes",
            "type" => "field",
            "format" => "string",
            "sortable" => true,
            "queryable" => false,
            "filterable" => false,
        ],
        [
            "name" => "visits",
            "type" => "field",
            "format" => "string",
            "sortable" => true,
            "queryable" => false,
            "filterable" => false,
        ],
        [
            "name" => "published",
            "type" => "field",
            "class" => "text-center",
            "format" => "checkbox",
            "sortable" => true,
            "queryable" => false,
            "filterable" => true,
        ],
        [
            "name" => "updated_at",
            "type" => "field",
            "format" => "datetime",
            "sortable" => true,
            "queryable" => false,
            "filterable" => false,
        ],
        [
            "name" => "created_at",
            "type" => "field",
            "format" => "datetime",
            "sortable" => true,
            "queryable" => false,
            "filterable" => false,
        ],
        [
            "name" => "actions",
            "type" => "callback",
            "class" => "flex",
            "format" => StaticsItemsTable::class . "::getActions",
            "sortable" => false,
            "queryable" => false,
            "filterable" => false,
        ],
    ];

    public static function getExcerpt($item)
    {
        return wordwrap(Str::limit(strip_tags($item->answer), 100), 35, "<br>");
    }

    public static function getCategoryTitle($item)
    {
        if ($item->category instanceof StaticsCategory) {
            return $item->category->title;
        }

        return __("(No Category)");
    }

    public function getFilters($request)
    {
        $params = $request["route"];
        $parent = isset($params["static_category"]) ? $params["static_category"] : null;
        $parent = StaticsCategory::find($parent["id"]);
        return function ($query) use ($parent) {
            if ($parent) {
                $categories = StaticsCategory::setParent($parent)->select("id")->get()->pluck("id");
                $query->whereIn("category_id", $categories);
            }
        };
    }

    public static function getActions($item)
    {
        if (static::$category == null) {
            static::$category = $item->baseCategory();
        }
        $parent = static::$category;
        return "<a href='" . route("paksuco-statics.category.items.frontshow", ["item" => $item]) . "' target='_blank'>
            <button type='button' class='px-3 py-1 mr-1 text-white bg-blue-700 rounded shadow'>" .
            __("Show") . "
            </button>
        </a>
        <a href='" . route("paksuco-statics.category.items.edit", ["static_category" => $parent, "item" => $item]) . "'>
            <button type='button' class='px-3 py-1 mr-1 text-white bg-indigo-700 rounded shadow'>" .
            __("Edit") . "
            </button>
        </a>" . ($item->is_deletable ?
                "<form action='" . route("paksuco-statics.category.items.destroy", ["static_category" => $parent, "item" => $item]) . "' method='POST'>
            <input name='_token'  type='hidden' value='" . csrf_token() . "'>
            <input name='_method' type='hidden' value='DELETE'>
            <button type='submit' class='px-3 py-1 text-white bg-red-700 rounded shadow'>" .
                __("Delete") .
                "</button>
        </form>" : "");
    }
}
