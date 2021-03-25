<?php

namespace Paksuco\Static\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Paksuco\Static\Models\StaticItem;

class StaticController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("paksuco-statics::backend.index", [
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
        return view("paksuco-statics::backend.form", [
            "extends" => config("paksuco-statics.backend.template_to_extend", "layouts.app"),
            "edit" => false,
            "categories" => StaticCategory::all(),
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
            "slug" => "unique:static_items,slug,NULL,id",
            "content" => "required|filled",
            "category_id" => "present",
            "publish" => "required|filled",
        ]);

        $static = new StaticItem();
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

        return redirect()->route("paksuco.static.index")->with("success", "Page has been successfully created.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(StaticItem $static)
    {
        return view("paksuco-statics::frontend.show", [
            "static" => $static,
            "extends" => config("paksuco-statics.frontend.template_to_extend", "layouts.app"),
        ]);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(StaticItem $static)
    {
        return view("paksuco-statics::backend.form", [
            "extends" => config("paksuco-statics.backend.template_to_extend", "layouts.app"),
            "edit" => true,
            "static" => $static,
            "categories" => StaticCategory::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StaticItem $static)
    {
        $request->validate([
            "title" => "required|filled"
        ]);

        $request->merge(["slug" => Str::slug($request->title)]);

        $request->validate([
            "slug" => "unique:static_items,slug,".$static->id.",id",
            "content" => "required|filled",
            "category_id" => "present",
            "publish" => "required|filled",
        ]);

        $static->question = $request->title;
        $static->answer = $request->content;
        $static->slug = Str::slug($request->title);
        if ($request->publish != "0") {
            $static->published = $request->publish == "1" ? true : false;
        }
        $static->save();

        return redirect()->route("paksuco.static.index")->with("success", "Page successfully updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(StaticItem $static)
    {
        $static->delete();
        return redirect()->route("paksuco.static.index")->with("success", "STATIC Item has been successfully deleted");
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
