@props([
    'debug' => config('app.debug'),
])

<script>
    window.flashMessages = {
        success: @json(session('success')),
        error:   @json(session('error')),
        warning: @json(session('warning')),
        info:    @json(session('info')),
        status:  @json(session('status')),
    };

    @if ($errors->any())
        window.validationErrors = @json($errors->messages());
    @else
        window.validationErrors = {};
    @endif

    window.appDebug = @json($debug);
</script>
