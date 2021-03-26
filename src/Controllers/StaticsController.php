<?php

namespace Paksuco\Statics\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Paksuco\Statics\Models\StaticsCategory;
use Paksuco\Statics\Models\StaticsItem;

class StaticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parent = $request->has("category") ? $request->category : null;
        $model  = $parent ? StaticsCategory::where("slug", $parent)->first() : null;
        $title  = $model ? \Illuminate\Support\Str::singular($model->title) . " Items" : "Static Items";

        return view("paksuco-statics::backend.index", [
            "extends" => config("paksuco-statics.backend.template_to_extend", "layouts.app"),
            "title" => $title
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("paksuco-statics::backend.form", [
            "extends" => config("paksuco-statics.backend.template_to_extend", "layouts.app"),
            "edit" => false,
            "categories" => StaticsCategory::all(),
            "static" => null
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "title" => "required|filled"
        ]);

        $request->merge(["slug" => Str::slug($request->title)]);

        $request->validate([
            "slug" => "unique:statics_items,slug,NULL,id",
            "content" => "required|filled",
            "category_id" => "present",
            "publish" => "required|filled",
        ]);

        $static = new StaticsItem();
        $static->category_id = $category_id ?? null;
        $static->title = $request->title;
        $static->slug = Str::slug($request->title);
        $static->content = $request->content;
        $static->published = $request->publish == "1" ? true : false;
        $static->order = 0;
        $static->likes = 0;
        $static->dislikes = 0;
        $static->visits = 0;
        $static->save();

        return redirect()->route("paksuco.statics.index")->with("success", "Page has been successfully created.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function frontindex()
    {
        return view("paksuco-statics::frontend.index", [
            "extends" => config("paksuco-statics.frontend.template_to_extend", "layouts.app"),
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function frontshow(StaticsItem $static)
    {
        return view("paksuco-statics::frontend.show", [
            "extends" => config("paksuco-statics.frontend.template_to_extend", "layouts.app"),
            "static" => $static
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(StaticsItem $static)
    {
        return view("paksuco-statics::backend.form", [
            "extends" => config("paksuco-statics.backend.template_to_extend", "layouts.app"),
            "edit" => true,
            "static" => $static,
            "categories" => StaticsCategory::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StaticsItem $static)
    {
        $request->validate([
            "title" => "required|filled"
        ]);

        $request->merge(["slug" => Str::slug($request->title)]);

        $request->validate([
            "slug" => "unique:statics_items,slug,".$static->id.",id",
            "content" => "required|filled",
            "category_id" => "present",
            "publish" => "required|filled",
        ]);

        $static->title = $request->title;
        $static->content = $request->content;
        $static->slug = Str::slug($request->title);
        if ($request->publish != "0") {
            $static->published = $request->publish == "1" ? true : false;
        }
        $static->save();

        return redirect()->route("paksuco.statics.index")->with("success", "Page successfully updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(StaticsItem $static)
    {
        $static->delete();
        return redirect()->route("paksuco.statics.index")->with("success", "STATIC Item has been successfully deleted");
    }

    public function upload(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => $validation->errors()->all(),
            ], 400);
        }

        $image = $request->file('file');
        $new_name = rand() . '.' . $image->getClientOriginalExtension();
        $path = config('paksuco-statics::backend.image_upload_folder', public_path('uploads'));
        $image->move($path, $new_name);

        $url = str_replace(public_path(), '', $path . "/" . $new_name);

        return response()->json([
            'location' => $url
        ]);
    }
}
