@extends($extends)
@section('content')
<!-- component -->
<div class="py-10 bg-gray-100">
    <div class="container mx-auto">
        <div class="min-h-screen p-2 bg-gray-200 rounded">
            <div class="flex flex-col md:flex-row">
                <div class="p-4 text-sm md:w-1/3">
                    <div class="text-3xl">@lang('Frequently asked') <span class="font-medium">@lang('Questions')</span></div>
                    <div class="my-2">@lang('Wondering how our service works?')</div>
                    <div class="mb-2">@lang('Confused about how we can improve your business?')</div>
                    <div class="text-xs text-gray-600">@lang('Dive into our FAQ for more details')</div>
                </div>
                <div class="md:w-2/3">
                    <div class="p-4">
                        @forelse(\Paksuco\Static\Models\StaticItem::all() as $faqitem)
                        <div class="mb-2">
                            <div class="flex flex-row-reverse px-2 py-3 mt-2 text-lg font-medium text-black text-gray-800 bg-white rounded-sm cursor-pointer hover:bg-white">
                                <div class="flex-auto">{{$faqitem->question}}</div>
                                <div class="pl-3 pr-4">
                                    <i class="text-sm fa fa-chevron-up"></i>
                                </div>
                            </div>
                            <div class="px-8 pt-4 pb-8 text-left text-justify text-gray-800 bg-white" style="">
                                {!! $faqitem->answer !!}
                            </div>
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
</div>
@endsection