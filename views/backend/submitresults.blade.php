<div id="error-container">
    @if ($errors->any())
    <x-paksuco-settings-alert color="red" textcolor="red-900" icon="fa fa-exclamation-triangle">
        <p class="pl-1 mb-2 font-normal">@lang("Oops, there was a problem, please check your input and submit the form again.")</p>
        <ul class="font-normal">
            @foreach ($errors->all() as $error)
            <li>- {{ $error }}</li>
            @endforeach
        </ul>
    </x-paksuco-settings-alert>
    @endif
    @if (session("success"))
    <x-paksuco-settings-alert title="success" color="green" textcolor="green-900" icon="fa fa-check">
        {{ session("success") }}
    </x-paksuco-settings-alert>
    @endif
    @if (session("error"))
    <x-paksuco-settings-alert title="error" color="red" textcolor="red-900" icon="fa fa-exclamation-triangle">
        {{ session("error") }}
    </x-paksuco-settings-alert>
    @endif
</div>
