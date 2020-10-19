<!doctype html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Site Maintenance - {{ config('app.name', app_setting('app-title')) }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="{{ mix('css/icon.css') }}" rel="stylesheet">
    <style>
        body { text-align: center; padding: 25px; }
        article { display: block; text-align: left; max-width: 650px; margin: 0 auto; }
    </style>
</head>
<body>
<article class="md:pt-40">
    <h1 class="font-bold mb-2 text-3xl md:text-5xl text-green-500">
        <i class="mdi mdi-application-cog"></i> We will be back soon!
    </h1>
    <div>
        <p class="mb-3 text-xl md:text-3xl">
            Sorry for the inconvenience but we're performing some maintenance at the moment.
            If you need to you can always <a href="mailto:{{ app_setting('email-support') }}" class="text-link">contact us</a>, otherwise we'll be back online shortly!
        </p>
        <p class="text-gray-600 text-lg">&mdash; {{ app_setting('app-title') }}</p>
    </div>
</article>
</body>
