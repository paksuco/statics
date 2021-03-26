@extends($extends)
@section("content")
<div class="min-h-screen p-8 border-t">
    <div class="items-end w-full">
        <div class="flex">
            <div class="w-2/3">
                <h2 class="mb-3 text-3xl font-semibold" style="line-height: 1em">{{__($title)}}</h2>
            </div>
            <div class="w-1/3 text-right">
                <a href="{{route('paksuco.statics.create')}}"
                class="px-3 py-2 font-normal text-white whitespace-no-wrap bg-indigo-500 rounded shadow hover:bg-indigo-400 focus:shadow-outline focus:outline-none">
                    <i class="mr-2 fa fa-plus"></i>@lang("Create a new Page")
                </a>
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
        @include("paksuco-statics::backend.submitresults")
        @livewire("paksuco-table::table", ["class" => new \Paksuco\Statics\Tables\StaticsItemsTable()])
    </div>
</div>
@endsection
