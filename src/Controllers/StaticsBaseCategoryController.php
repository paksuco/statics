<?php

namespace Paksuco\Statics\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Paksuco\Statics\Models\StaticsCategory;

class StaticsBaseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(StaticsCategory $static_category)
    {
        $category   = $static_category;
        $title      = Str::singular($category->title) . " Categories";
        $categories = StaticsCategory::setParent($category)->select(["id", "title"])->get()->pluck("title", "id");

        return view("paksuco-statics::backend.categories", [
            "extends"    => config("paksuco-statics.backend.template_to_extend", "layouts.app"),
            "categories" => $categories,
            "title"      => $title,
            "parent"     => $category
        ]);
    }
}
