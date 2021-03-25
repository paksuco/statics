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
    public $perPage = 10;

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
            "name" => 'question',
            "type" => "field",
            "class" => "w-full bg-gray-50",
            "format" => "string",
            "sortable" => true,
            "queryable" => true,
            "filterable" => false,
        ],
        [
            "name" => "answer",
            "type" => "callback",
            "format" => StaticsItemsTable::class . "::getExcerpt",
            "sortable" => true,
            "queryable" => true,
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

    public static function getActions($item)
    {
        return "<a href='". route("paksuco.static.show", $item) . "'>
            <button type='button' class='mr-1 rounded px-3 py-1 bg-blue-700 text-white shadow'>" .
                __("Show") . "
            </button>
        </a>
        <a href='". route("paksuco.static.edit", $item) . "'>
            <button type='button' class='mr-1 rounded px-3 py-1 bg-indigo-700 text-white shadow'>" .
                __("Edit") . "
            </button>
        </a>
        <form action='" . route("paksuco.static.destroy", $item) . "' method='POST'>
            <input name='_token'  type='hidden' value='".csrf_token()."'>
            <input name='_method' type='hidden' value='DELETE'>
            <button type='submit' class='rounded px-3 py-1 bg-red-700 text-white shadow'>" .
                __("Delete") .
            "</button>
        </form>";
    }
}
