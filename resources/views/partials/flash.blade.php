@if (\Session::has('flash_message'))
    <div class="alert alert-{{session('flash_message')['type']}} {{ \Session::has('flash_message_important') ? 'alert-important' : '' }}">
        @if (\Session::has('flash_message_important'))
            <button class="close" type="button" data-dismiss="alert" aria-hidden="true">&times;</button>
        @endif

        {{ session('flash_message')['message'] }}
    </div>
@endif