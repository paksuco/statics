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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("paksuco-statics::backend.categories", [
            "extends" => config("paksuco-statics.backend.template_to_extend", "layouts.app"),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // not implemented on separate page
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge([
            "slug" => Str::slug($request->title ?? "")
        ]);

        $request->validate([
            "title" => "required|filled",
            "slug" => "unique:statics_categories,slug,NULL,id",
            "description" => "present"
        ]);

        $category = new StaticsCategory();
        $category->title = $request->title;
        $category->slug = Str::slug($request->title);
        $category->description = $request->description;
        $category->order = 0;
        $category->parent_id = $request->parent_id ?? null;
        $category->save();

        return redirect()->route("paksuco.staticcategory.index")
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
    public function frontshow(StaticsCategory $category)
    {
        return view("paksuco-statics::frontend.showcategory", [
            "statics" => $category->items,
            "extends" => config("paksuco-statics.frontend.template_to_extend", "layouts.app"),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $static = StaticsItem::findOrFail($id);

        return view("paksuco-statics::backend.form", [
            "extends" => config("paksuco-statics.backend.template_to_extend", "layouts.app"),
            "edit" => true,
            "static" => $static,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->merge([
            "slug" => Str::slug($request->title ?? "")
        ]);

        $request->validate([
            "id" => "required|exists:statics_categories,id",
            "title" => "required|filled",
            "slug" => "unique:statics_categories,slug,$id,id",
            "description" => "present",
            "parent_id" => "present|not_in:$id"
        ]);

        $category = StaticsCategory::find($id);
        $category->title = $request->title;
        $category->slug = Str::slug($request->title);
        $category->description = $request->description;
        $category->order = 0;
        $category->parent_id = $request->parent_id ?? null;
        $category->save();

        return redirect()->route("paksuco.staticcategory.index")
            ->with("success", "Category successfully updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(StaticsCategory $category)
    {
        $category->delete();

        return redirect()
            ->route("paksuco.staticcategory.index")
            ->with("success", "Category has been successfully deleted.");
    }
}
