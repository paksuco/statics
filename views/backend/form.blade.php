@extends($extends)
@section("content")
<div class="p-8 border-t">
    @include("paksuco-statics::backend.submitresults")
    <form method="POST" action="{{$edit
    ? route('paksuco-statics.category.items.update', ['static_category' => $category, 'item' =>  $static])
    : route('paksuco-statics.category.items.store', ['static_category' => $category])}}">
        @if($edit)
        @method("PUT")
        @endif
        @csrf
        <div class="items-end w-full">
            <div class="flex mb-4">
                <div class="w-2/3">
                    <h2 class="mb-3 text-3xl font-semibold" style="line-height: 1em">
                        {{$edit ? __("Edit $title") : __("Create a new $title")}}
                    </h2>
                </div>
                <div class="w-1/3 text-right">
                    <button type="submit" name="publish" value="0"
                        class="px-4 py-2 text-white bg-blue-400 border border-blue-500 rounded shadow">@lang('Save')</button>

                    @if($edit == false || $static->published == false)
                        <button type="submit" name="publish" value="1"
                            class="px-4 py-2 text-white bg-green-400 border border-green-500 rounded shadow">@lang('Save &amp; Publish')</button>
                    @else
                        <button type="submit" name="publish" value="-1"
                            class="px-4 py-2 text-white bg-red-700 border border-red-800 rounded shadow">@lang('Un-publish')</button>
                    @endif
                    <button type="button" class="px-4 py-2 border rounded shadow"
                        onclick="window.history.back();">@lang('Go Back')</button>
                </div>
            </div>
            <input type="text" name="title" placeholder="@lang('Enter Title')"
                class="w-full p-2 px-4 mb-3 text-2xl border rounded-sm shadow-inner" value="{{$edit ? (old("title") ?? $static->title) : old("title")}}">
            <select name="category_id" class="w-full p-2 px-4 mb-3 text-xl border rounded-sm shadow-inner form-select">
                <option value="">@lang("- Select a Category -")</option>
                @foreach ($categories as $category)
                    <option value="{{$category->id}}"
                        @if($edit && $static->category_id == (old("category_id") ?? $category->id)) selected @endif
                        @if($edit == false && $category->id == old("category_id")) selected @endif
                        >{{$category->title}}</option>
                @endforeach
            </select>
            <textarea id="mytextarea" name="content" class="shadow">{{$edit ? (old("content") ?? $static->content) : old("content")}}</textarea>
        </div>
    </form>
</div>
@endsection

@push("head-scripts")
<script src="/assets/vendor/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    tinymce.init({
        selector: '#mytextarea',
        height: 500,
        language: "{{config('app.locale')}}",
        plugins: 'preview powerpaste searchreplace autolink \
            directionality advcode visualblocks visualchars fullscreen image \
            link media template codesample table charmap hr faqbreak nonbreaking \
            anchor toc insertdatetime advlist lists textcolor wordcount \
            tinymcespellchecker a11ychecker imagetools mediaembed linkchecker \
            contextmenu colorpicker textpattern code',
        toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | \
            link | alignleft aligncenter alignright alignjustify  | numlist bullist \
            outdent indent  | removeformat | code',
        image_advtab: true,
        templates: [
            { title: 'Test template 1', content: 'Test 1' },
            { title: 'Test template 2', content: 'Test 2' }
        ],
        relative_urls : false,
        remove_script_host : false,
        convert_urls : true,
        images_upload_handler: function (blobInfo, success, failure) {
           var xhr, formData;
           xhr = new XMLHttpRequest();
           xhr.withCredentials = false;
           xhr.open('POST', '{{route("paksuco-statics.upload")}}');
           var token = '{{ csrf_token() }}';
           xhr.setRequestHeader("X-CSRF-Token", token);
           xhr.onload = function() {
               var json;
               if (xhr.status != 200) {
                   failure('HTTP Error: ' + xhr.status);
                   return;
               }
               json = JSON.parse(xhr.responseText);

               if (!json || typeof json.location != 'string') {
                   failure('Invalid JSON: ' + xhr.responseText);
                   return;
               }
               success(json.location);
           };
           formData = new FormData();
           formData.append('file', blobInfo.blob(), blobInfo.filename());
           xhr.send(formData);
       }
    });
</script>
@endpush
