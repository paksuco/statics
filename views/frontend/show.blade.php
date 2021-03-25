@extends($extends)
@section("content")
<div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <a href="javascript:history.back();">&lsaquo; Go Back</a>
<h1 class="py-8 text-5xl font-semibold">{{$static->title}}</h1>
{!!$static->content!!}
</div>
@endsection
