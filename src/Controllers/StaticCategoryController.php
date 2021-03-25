<?php

namespace Paksuco\Static\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Paksuco\Static\Models\StaticCategory;
use Paksuco\Static\Models\StaticItem;

class StaticCategoryController extends Controller
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
            "slug" => "unique:static_categories,slug,NULL,id",
            "description" => "present"
        ]);

        $category = new StaticCategory();
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
    public function show($id)
    {
        $static = StaticItem::findOrFail($id);

        return view("paksuco-statics::frontend.show", [
            "static" => $static,
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
        $static = StaticItem::findOrFail($id);

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
            "id" => "required|exists:static_categories,id",
            "title" => "required|filled",
            "slug" => "unique:static_categories,slug,$id,id",
            "description" => "present",
            "parent_id" => "present|not_in:$id"
        ]);

        $category = StaticCategory::find($id);
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
    public function destroy(StaticCategory $category)
    {
        $category->delete();

        return redirect()
            ->route("paksuco.staticcategory.index")
            ->with("success", "Category has been successfully deleted.");
    }
}
