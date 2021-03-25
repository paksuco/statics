@extends($extends)
@section("body")
<div class="my-8 container mx-auto mb-48">
<h1 class="py-8 text-5xl font-semibold">{{$static->title}}</h1>
{!!$static->content!!}
</div>
@endsection