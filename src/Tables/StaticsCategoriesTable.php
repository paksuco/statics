<?php

namespace Paksuco\Statics\Tables;

use Paksuco\Statics\Models\StaticsCategory;

class StaticsCategoriesTable extends \Paksuco\Table\Contracts\TableSettings
{
    public $model = StaticsCategory::class;
    public $relations = ["parent"];
    public $queryable = true;
    public $sortable = true;
    public $pageable = true;
    public $perPages = [10, 25, 50, 100];
    public $perPage = 10;
    public $appends = ["category"];

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
            "class" => "",
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
            "class" => "flex",
            "format" => StaticsCategoriesTable::class . "::getActions",
            "sortable" => false,
            "queryable" => false,
            "filterable" => false,
        ],
    ];

    public function __construct()
    {
        parent::__construct();
        $this->appends = request()->query();
        dump($this->appends);
    }

    public function getFilters()
    {
        return function ($query) {
            $request = request();
            $parent = $request->has("category") ? $request->category : null;
            if ($parent) {
                $query->setParent($parent);
            }
        };
    }

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
            <button type='button' class='px-3 py-1 mr-1 text-white bg-indigo-700 rounded shadow' onclick='editCategory({$item->id})'>" . __("Edit") . "</button>
        </a>
        <form action='" . route("paksuco.staticcategory.destroy", $item->id) . "' method='POST'>
            <input name='_token'  type='hidden' value='" . csrf_token() . "'>
            <input name='_method' type='hidden' value='DELETE'>
            <button type='submit' class='px-3 py-1 text-white bg-red-700 rounded shadow'>" . __("Delete") . "</button>
        </form>";
    }
}
