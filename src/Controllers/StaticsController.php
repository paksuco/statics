<?php

namespace Paksuco\Statics\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;
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
    public function index(StaticsCategory $static_category)
    {
        $model = $static_category;
        $title = Str::singular($model->title) . " Items";

        return view("paksuco-statics::backend.index", [
            "extends" => config("paksuco-statics.backend.template_to_extend", "layouts.app"),
            "title" => $title,
            "parent" => $model
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(StaticsCategory $static_category)
    {
        return view("paksuco-statics::backend.form", [
            "extends" => config("paksuco-statics.backend.template_to_extend", "layouts.app"),
            "edit" => false,
            "category" => $static_category,
            "title" => Str::singular($static_category->title),
            "categories" => StaticsCategory::setParent($static_category)->get(),
            "static" => null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, StaticsCategory $static_category)
    {
        $request->validate([
            "title" => "required|filled",
        ]);

        $request->merge(["slug" => Str::slug($request->title)]);

        $request->validate([
            "content" => "required|filled",
            "category_id" => "required|bail",
            "slug" => "unique:statics_items,slug,NULL,id,category_id," . $request->category_id,
            "publish" => "required|filled",
        ]);

        $static = new StaticsItem();
        $static->category_id = $request->category_id ?? null;
        $static->title = $request->title;
        $static->slug = Str::slug($request->title);
        $static->content = $request->content;
        $static->published = $request->publish == "1" ? true : false;
        $static->order = 0;
        $static->likes = 0;
        $static->dislikes = 0;
        $static->visits = 0;
        $static->save();

        return redirect()->route("paksuco-statics.category.items.index", ["static_category" => $static_category])->with("success", "Page has been successfully created.");
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
    public function frontshow(StaticsItem $item)
    {
        $content = $item->content;
        $matches = null;
        if (preg_match("/\[link-to-route:(.+)\]/", $content, $matches) > 0) {
            $route = $matches[1];
            if (Route::has($route)) {
                return redirect()->route($route);
            };
        } elseif (preg_match("/\[link-to-category:(.+)\]/", $content, $matches) > 0) {
            $slug = $matches[1];
            $category = StaticsCategory::where('slug', '=', $slug)->first();
            if ($category instanceof StaticsCategory) {
                return redirect()->route("paksuco.staticcategory.frontshow", ["category" => $category]);
            };
        } elseif (preg_match("/\[link-to-page:(.+)\]/", $content, $matches) > 0) {
            $path = explode(".", $matches[1]);
            if (count($path) == 2) {
                list($category, $slug) = $path;
            } elseif (count($path) == 1) {
                list($category, $slug) = [null, $path];
            } else {
                list($category, $slug) = [null, null];
            }
            if ($slug) {
                $page = null;
                if ($category) {
                    $category = StaticsCategory::where("slug", "=", $category)
                        ->first();
                    if ($category instanceof StaticsCategory) {
                        $page = StaticsItem::where(
                            [
                                "slug" => $slug,
                                "category_id" => $category->id
                            ]
                        )->first();
                        if ($page instanceof StaticsItem) {
                            return redirect()->route(
                                "paksuco.statics.frontshow",
                                [
                                    "static" => $page
                                ]
                            );
                        };
                    }
                } else {
                    $page = StaticsItem::where('slug', '=', $slug)->first();
                    if ($page instanceof StaticsItem) {
                        return redirect()->route(
                            "paksuco.statics.frontshow",
                            [
                                "static" => $page
                            ]
                        );
                    };
                }
            }
        }

        return view("paksuco-statics::frontend.show", [
            "extends" => config("paksuco-statics.frontend.template_to_extend", "layouts.app"),
            "static" => $item,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(StaticsCategory $static_category, StaticsItem $item)
    {
        return view("paksuco-statics::backend.form", [
            "extends" => config("paksuco-statics.backend.template_to_extend", "layouts.app"),
            "edit" => true,
            "category" => $static_category,
            "static" => $item,
            "title" => Str::singular($static_category->title),
            "categories" => StaticsCategory::setParent($static_category)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StaticsCategory $static_category, StaticsItem $item)
    {
        $request->validate([
            "title" => "required|filled",
        ]);

        $request->merge(["slug" => Str::slug($request->title)]);

        $request->validate([
            "content" => "required|filled",
            "category_id" => "required|bail",
            "publish" => "required|filled",
            "slug" => "unique:statics_items,slug," . $item->id . ",id,category_id," . $request->category_id,
        ]);

        $item->title = $request->title;
        $item->content = $request->content;
        $item->category_id = $request->category_id;
        $item->slug = Str::slug($request->title);
        if ($request->publish != "0") {
            $item->published = $request->publish == "1" ? true : false;
        }
        $item->save();

        return redirect()->route("paksuco-statics.category.items.index", ["static_category" => $static_category])->with("success", __("Page successfully updated"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(StaticsCategory $static_category, StaticsItem $item)
    {
        if ($item->is_deletable) {
            $item->delete();
        }
        return redirect()->route("paksuco-statics.category.items.index", ["static_category" => $static_category])->with("success", __("Page has been successfully deleted"));
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
            'location' => $url,
        ]);
    }
}
