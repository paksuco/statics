@extends($extends)
@section("content")
<div class="flex min-h-screen border-t">
    <div class="flex-shrink-0 p-2 pt-6 w-60">
        <form action="{{route('paksuco.staticcategory.store')}}"
            method="POST"
            id="new_category_form"
            x-data="{}">
            <h2 class="mx-3 mb-3 text-xl font-semibold leading-6 add_form_visible">@lang('Add New Category')</h2>
            <h2 class="hidden mx-3 mb-3 text-xl font-semibold leading-6 edit_form_visible">@lang('Edit Category')</h2>

            <input type="hidden" name="_method" value="POST" id="category_submit_type">
            <input type="hidden" name="id" value="" id="category_submit_id">
            @csrf
            @livewire("paksuco-settings::textinput", [
            "title" => __("Category Name"),
            "key" => "title",
            "model" => "",
            "value" => ""
            ], key("title"))
            @livewire("paksuco-settings::textarea", [
            "title" => __("Description"),
            "key" => "description",
            "model" => "",
            "value" => "",
            "props" => [
            "rows" => 5
            ]
            ], key("description"))
            @livewire("paksuco-settings::selectinput", [
            "title" => __("Parent Category"),
            "key" => "parent_id",
            "model" => "",
            "value" => "",
            "props" => ["values" => \Paksuco\Support\Models\StaticCategory::select(["id",
            "title"])->get()->pluck("title", "id")]
            ], key("parent"))
            <div class="text-right">
                <button type="button"
                    class="hidden px-3 py-1 mr-1 text-gray-800 bg-white border border-gray-100 rounded shadow edit_form_visible"
                    onclick="resetForm(this)">@lang('Cancel')</button>
                <button type="submit" class="px-3 py-1 mr-3 text-white bg-blue-500 border border-blue-600 rounded shadow">@lang('Save')</button>
            </div>
        </form>
    </div>
    <div class="flex-1 p-6 bg-white shadow-lg">
        <div class="items-end w-full">
            @include("paksuco-statics::backend.submitresults")
            <div class="flex">
                <div class="w-2/3">
                    <h2 class="mb-3 text-3xl font-semibold" style="line-height: 1em">@lang("Categories")</h2>
                </div>
                <div class="w-1/3 text-right">
                    &nbsp;
                </div>
            </div>
            <p class="mb-4 text-sm font-light leading-5 text-gray-600">Lorem ipsum dolor sit amet, consectetur
                adipiscing elit. Proin interdum urna sit amet lorem iaculis, aliquet suscipit sapien venenatis.
                Sed congue vitae velit vitae varius. Mauris egestas consequat mauris sit amet mollis. Proin porta
                tortor in urna tincidunt vehicula. Integer urna nulla, porttitor ac imperdiet eu, mattis vel lacus.
                Sed et porttitor ex. Morbi pellentesque massa a velit gravida, vitae rutrum tortor consequat. Donec
                interdum lacus ut sem consectetur elementum. Proin pellentesque maximus sem sed rhoncus. Cras eget
                neque a nisi posuere mollis vitae vitae magna. Praesent non volutpat sem, a maximus libero.
            </p>
            <div id="category_table">
                @include("paksuco-statics::backend.categories_table")
            </div>
        </div>
    </div>
</div>
<script>
    var editCategory = function(row) {
        fetch("/api/staticcategory/" + row, {
            method: 'GET',
            mode: 'cors',
            cache: 'no-cache',
            credentials: 'same-origin',
            headers: {
            'Accept': 'application/json'
            },
        })
        .then( response => response.json())
        .then((data) => {
            var form = document.querySelector("#new_category_form");
            form.action = "{{route('paksuco.staticcategory.index')}}/"+data.id;
            form.querySelector("[name='_method']").value = "PUT";
            form.querySelector("[name='id']").value = data.id;
            form.querySelector("[x-ref='title']").value = data.title;
            form.querySelector("[x-ref='description']").innerText = data.description;
            form.querySelector("[x-ref='parent_id']").value = data.parent_id;
            form.querySelectorAll(".edit_form_visible").forEach( i => i.classList.remove("hidden"));
            form.querySelectorAll(".add_form_visible").forEach( i => i.classList.add("hidden"));
        });
    };
    var resetForm = function(){
        var form = document.querySelector("#new_category_form");
            form.querySelector("[name='_method']").value = "POST";
            form.action = "{{route('paksuco.staticcategory.store')}}";
            form.querySelector("[name='id']").value = "";
            form.querySelector("[x-ref='title']").value = "";
            form.querySelector("[x-ref='description']").innerText = "";
            form.querySelector("[x-ref='parent_id']").value = "";
            form.querySelectorAll(".edit_form_visible").forEach( i => i.classList.add("hidden"));
            form.querySelectorAll(".add_form_visible").forEach( i => i.classList.remove("hidden"));
    };
</script>
@endsection