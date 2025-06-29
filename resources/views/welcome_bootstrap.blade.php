<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.welcome') }} - CV Builder</title>
    <!-- Bootstrap 5.3 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h1 class="text-center mb-4">{{ __('messages.welcome') }}</h1>
        <p class="lead text-center">{{ __('messages.description') }}</p>

        <form action="{{ route('locale.switch') }}" method="post" class="text-center mt-4">
            @csrf
            <button name="locale" value="en" class="btn btn-primary me-2">{{ __('messages.english') }}</button>
            <button name="locale" value="tr" class="btn btn-secondary">{{ __('messages.turkish') }}</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
