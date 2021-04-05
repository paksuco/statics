@extends($extends)
@section("content")
<div class="min-h-screen p-8 border-t">
    <div class="items-end w-full">
        <div class="flex">
            <div class="w-2/3">
                <h2 class="mb-3 text-3xl font-semibold" style="line-height: 1em">{{__($title)}}</h2>
            </div>
            <div class="w-1/3 text-right">
                <a href="{{route('paksuco-statics.category.items.create', ['static_category' => $parent])}}"
                class="px-3 py-2 font-normal text-white whitespace-no-wrap bg-indigo-500 rounded shadow hover:bg-indigo-400 focus:shadow-outline focus:outline-none">
                    <i class="mr-2 fa fa-plus"></i>@lang("Create a new " . \Illuminate\Support\Str::singular($title))
                </a>
            </div>
        </div>
        @include("paksuco-statics::backend.submitresults")
        @livewire("paksuco-table::table", ["class" => new \Paksuco\Statics\Tables\StaticsItemsTable(), "extras" => [
            "parent" => $parent
        ]])
    </div>
</div>
@endsection
