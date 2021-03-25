@extends($extends)
@section('content')
<!-- component -->
<div class="py-10 bg-gray-100">
    <div class="container mx-auto">
        <div class="min-h-screen p-2 bg-gray-200 rounded">
            <div class="flex flex-col md:flex-row">
                <div class="p-4">
                    @forelse($statics as $static)
                    <div class="mb-2">
                        <a href="{{route("paksuco.statics.frontshow", ["static" => $static])}}">
                        <div class="flex flex-row-reverse px-2 py-3 mt-2 text-lg font-medium text-black text-gray-800 bg-white rounded-sm cursor-pointer hover:bg-white">
                            <div class="flex-auto">{{$static->title}}</div>
                            <div class="pl-3 pr-4">
                                <i class="text-sm fa fa-chevron-up"></i>
                            </div>
                        </div>
                        <div class="px-8 pt-4 pb-8 text-left text-justify text-gray-800 bg-white" style="">
                            {!! $static->excerpt !!}
                        </div>
                        </a>
                    </div>
                    @empty
                    <div class="mb-2">
                        <div class="px-8 pt-4 pb-8 text-left text-justify text-gray-800" style="">
                            @lang('There is nothing to show here. Perhaps the site admin forgot to add some questions?')
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
