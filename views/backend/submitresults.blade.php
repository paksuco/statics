@if ($errors->any())
<x-paksuco-settings-alert color="red" textcolor="white" icon="fa fa-exclamation-triangle">
    <p class="mb-2 pl-1">@lang("Oops, there was a problem, please check your input and submit the form again.")</p>
    <ul>
        @foreach ($errors->all() as $error)
        <li>- {{ $error }}</li>
        @endforeach
    </ul>
</x-paksuco-settings-alert>
@endif
@if (session("success"))
<x-paksuco-settings-alert title="success" color="green" textcolor="white" icon="fa fa-check">
    {{ session("success") }}
</x-paksuco-settings-alert>
@endif