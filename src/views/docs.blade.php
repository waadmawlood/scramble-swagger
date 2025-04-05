<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('scramble-swagger/css/swagger-ui.css') }}">
    <style>
        .swagger-ui .info { margin: 15px 0; }
        .swagger-ui .scheme-container { padding: 5px 0 15px; }
    </style>
    <link rel="stylesheet" href="{{ asset('scramble-swagger/css/custom-style.css') }}">
</head>

<body>
    <div id="scramble-swagger-ui"></div>

    <script src="{{ asset('scramble-swagger/js/swagger-ui-bundle.js') }}"></script>
    <script src="{{ asset('scramble-swagger/js/swagger-ui-standalone-preset.js') }}"></script>

    <script>
        window.onload = function() {
            window.ui = SwaggerUIBundle({
                urls: [
                    @foreach ($versions['versions'] as $version)
                        {
                            url: '{{ $version['url'] }}',
                            name: '{{ $version['name'] }}',
                        },
                    @endforeach
                ],
                "urls.primaryName": "{{ $versions['default'] }}",
                dom_id: '#scramble-swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                layout: 'StandaloneLayout',
                requestInterceptor: (request) => {
                    request.headers['accept'] = 'application/json';
                    return request;
                },
                responseInterceptor: (response) => {
                    response.headers['content-type'] = 'application/json';
                    response.headers['accept'] = 'application/json';
                    return response;
                }
            });
        };
    </script>
</body>
</html>
