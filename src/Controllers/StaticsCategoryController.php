<?php

namespace Paksuco\Statics\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Paksuco\Statics\Models\StaticsCategory;
use Paksuco\Statics\Models\StaticsItem;

class StaticsCategoryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, StaticsCategory $static_category)
    {
        $request->merge([
            "slug" => Str::slug($request->title ?? "")
        ]);

        $request->validate([
            "title" => "required|filled",
            "slug" => "unique:statics_categories,slug,NULL,id,parent_id,$static_category->id",
            "order" => "required|filled|numeric",
            "description" => "present"
        ]);

        $category = new StaticsCategory();
        $category->title = $request->title;
        $category->slug = Str::slug($request->title);
        $category->description = $request->description;
        $category->order = $request->order;
        $category->parent_id = $request->parent_id ?? null;
        $category->save();

        return redirect()->route("paksuco-statics.category.base.show", ["static_category" => $static_category])
            ->with("success", "Category successfully created.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function frontindex()
    {
        return view("paksuco-statics::frontend.categoryindex", [
            "extends" => config("paksuco-statics.frontend.template_to_extend", "layouts.app"),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function frontshow(StaticsCategory $static_category)
    {
        return view("paksuco-statics::frontend.showcategory", [
            "statics" => $static_category->items,
            "extends" => config("paksuco-statics.frontend.template_to_extend", "layouts.app"),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  StaticsCategory  $static_category  The base category
     * @param  StaticsCategory  $category         The category being edited
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StaticsCategory $static_category, StaticsCategory $category)
    {
        $request->merge([
            "slug" => Str::slug($request->title ?? "")
        ]);

        $request->validate([
            "id" => "required|exists:statics_categories,id",
            "title" => "required|filled",
            "order" => "required|filled|numeric",
            "slug" => "unique:statics_categories,slug,$category->id,id,parent_id,$static_category->id",
            "description" => "present",
            "parent_id" => "required|numeric|min:1|not_in:$category->id"
        ]);

        $category->title = $request->title;
        $category->slug = Str::slug($request->title);
        $category->description = $request->description;
        $category->order = $request->order;
        $category->parent_id = $request->parent_id;
        $category->save();

        return redirect()->route("paksuco-statics.category.base.show", ["static_category" => $static_category])
            ->with("success", "Category successfully updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(StaticsCategory $static_category, StaticsCategory $category)
    {
        if ($category->is_deletable) {
            $category->delete();
        }
        return redirect()
            ->route("paksuco-statics.category.base.show", ["static_category" => $static_category])
            ->with("success", "Category has been successfully deleted.");
    }
}
