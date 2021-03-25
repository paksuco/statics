@extends($extends)
@section("content")
<div class="flex min-h-screen border-b">
    <div class="flex-shrink-0 px-6 py-8 bg-cool-gray-200 w-80">
        <form action="{{route('paksuco.staticcategory.store')}}" method="POST" id="new_category_form" x-data="{}">
            <h2 class="mb-3 text-xl font-semibold leading-6 add_form_visible">@lang('Add New Category')</h2>
            <h2 class="hidden mb-3 text-xl font-semibold leading-6 edit_form_visible">@lang('Edit Category')</h2>
            <input type="hidden" name="_method" value="POST" id="category_submit_type">
            <input type="hidden" name="id" value="" id="category_submit_id">
            @csrf
            <div class="mb-3">
                <label class="w-full text-sm font-semibold">@lang("Category Name")</label>
                <div class="w-full"><input type="text" x-ref="title" class="w-full form-input"></div>
            </div>
            <div class="mb-3">
                <label class="w-full text-sm font-semibold">@lang("Description")</label>
                <div class="w-full"><textarea x-ref="description" class="w-full form-textarea" rows=5></textarea></div>
            </div>
            <div class="mb-3">
                <label class="text-sm font-semibold">@lang("Parent Category")</label>
                <div class="w-full">
                    <select x-ref="parent_id" class="w-full form-select">
                        @unless(request()->has("category"))
                        <option value="">@lang("-- No Parent --")</option>
                        @endif
                        @foreach ($categories as $id => $category)
                            <option value="{{$id}}">{{$category}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="text-right">
                <button type="button" class="hidden px-3 py-1 mr-1 text-gray-800 bg-white border border-gray-100 rounded shadow edit_form_visible" onclick="resetForm(this)">@lang('Cancel')</button>
                <button type="submit" class="px-3 py-1 mr-3 text-white bg-blue-500 border border-blue-600 rounded shadow">@lang('Save')</button>
            </div>
        </form>
    </div>
    <div class="flex-1 p-8 bg-cool-gray-50 md:border-l">
        <div class="items-end">
            @include("paksuco-statics::backend.submitresults")
            <div class="flex">
                <div class="w-2/3">
                    <h2 class="mb-3 text-3xl font-semibold" style="line-height: 1em">{{__($title)}}</h2>
                </div>
                <div class="w-1/3 text-right">
                    &nbsp;
                </div>
            </div>
            <div id="category_table">
                @include("paksuco-statics::backend.categories_table")
            </div>
        </div>
    </div>
</div>
<script>
    var editCategory = function(row) {
        console.log(row);
        fetch("/api/staticcategory/" + row, {
                method: 'GET'
                , mode: 'cors'
                , cache: 'no-cache'
                , credentials: 'same-origin'
                , headers: {
                    'Accept': 'application/json'
                }
            , })
            .then(response => response.json())
            .then((data) => {
                var form = document.querySelector("#new_category_form");
                form.action = "{{route('paksuco.staticcategory.index')}}/" + data.id;
                form.querySelector("[name='_method']").value = "PUT";
                form.querySelector("[name='id']").value = data.id;
                form.querySelector("[x-ref='title']").value = data.title;
                form.querySelector("[x-ref='description']").innerText = data.description;
                form.querySelector("[x-ref='parent_id']").value = data.parent_id;
                form.querySelectorAll(".edit_form_visible").forEach(i => i.classList.remove("hidden"));
                form.querySelectorAll(".add_form_visible").forEach(i => i.classList.add("hidden"));
            });
    };
    var resetForm = function() {
        var form = document.querySelector("#new_category_form");
        form.querySelector("[name='_method']").value = "POST";
        form.action = "{{route('paksuco.staticcategory.store')}}";
        form.querySelector("[name='id']").value = "";
        form.querySelector("[x-ref='title']").value = "";
        form.querySelector("[x-ref='description']").innerText = "";
        form.querySelector("[x-ref='parent_id']").value = "";
        form.querySelectorAll(".edit_form_visible").forEach(i => i.classList.add("hidden"));
        form.querySelectorAll(".add_form_visible").forEach(i => i.classList.remove("hidden"));
    };

</script>
@endsection
