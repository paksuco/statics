<?php

namespace Paksuco\Support\Tables;

use Paksuco\Support\Models\StaticsCategory;

class StaticsCategoriesTable extends \Paksuco\Table\Contracts\TableSettings
{
    public $model = StaticsCategory::class;
    public $relations = ["parent"];
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
            "name" => "title",
            "type" => "field",
            "format" => "string",
            "class" => "w-full bg-gray-50",
            "sortable" => true,
            "queryable" => true,
            "filterable" => false,
        ],
        [
            "name" => 'description',
            "type" => "field",
            "format" => "string",
            "sortable" => false,
            "queryable" => true,
            "filterable" => false,
        ],
        [
            "name" => "parent",
            "type" => "callback",
            "format" => StaticsCategoriesTable::class . "::getParentTitle",
            "sortable" => true,
            "queryable" => true,
            "filterable" => true,
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
            "format" => StaticsCategoriesTable::class . "::getActions",
            "sortable" => false,
            "queryable" => false,
            "filterable" => false,
        ],
    ];

    public static function getParentTitle($category)
    {
        if ($category->parent instanceof StaticsCategory) {
            return $category->parent->title;
        }

        return __("(No Parent)");
    }

    public static function getActions($item)
    {
        return "<a href='#new_category_form'>
            <button type='button' class='mr-1 rounded px-3 py-1 bg-indigo-700 text-white shadow' @click='editCategory({$item->id})'>" .
                __("Edit") . "
            </button>
        </a>
        <form action='" . route("paksuco.staticcategory.destroy", $item) . "' method='POST'>
            <input name='_token'  type='hidden' value='".csrf_token()."'>
            <input name='_method' type='hidden' value='DELETE'>
            <button type='submit' class='rounded px-3 py-1 bg-red-700 text-white shadow'>" .
                __("Delete") .
            "</button>
        </form>";
    }
}
