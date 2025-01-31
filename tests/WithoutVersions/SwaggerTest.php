<?php

use function Pest\Laravel\get;

it('swagger ui endpoint is accessible', function () {
    $response = get('docs/swagger');
    expect($response->status())->toBe(200)
        ->and($response->getContent())
        ->toContain('<div id="scramble-swagger-ui"></div>')
        ->toContain("url: 'http://localhost/docs/swagger/json?version=all'");
});

it('swagger ui is disabled when config is false', function () {
    config(['scramble-swagger.enable' => false]);

    $response = get('docs/swagger');
    expect($response->status())->toBe(404);
});

it('swagger ui url can be configured', function () {
    $response = get('doc/doc');
    expect($response->status())->toBe(404);
});
