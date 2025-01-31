<?php

use function Pest\Laravel\get;

it('api documentation json is accessible', function () {
    $response = get('docs/swagger/json');

    expect($response->status())->toBe(200);
    $json = $response->getContent();
    expect($json)->toBeJson();

    // main
    $array = json_decode($json, true);
    expect($array)->toHaveKey('openapi');
    expect($array)->toHaveKey('info');
    expect($array)->toHaveKey('servers');
    expect($array)->toHaveKey('paths');
    expect($array)->toHaveKey('components');

    // sub
    expect($array)->toHaveKey('servers.0.url');
    expect($array)->toHaveKey('info.title');
    expect($array)->toHaveKey('info.version');
    expect($array)->toHaveKey('paths./v1/test');
    expect($array)->toHaveKey('paths./v1/test/test');
    expect($array)->toHaveKey('paths./v2/test');
    expect($array)->toHaveKey('paths./v2/test/test');
    expect($array)->toHaveKey('components.responses');
    expect($array)->toHaveKey('components.paths');
});

it('test controller endpoint returns correct data', function () {
    $response = get('api/v2/test?per_page=2&page=1');

    $response->assertStatus(200)
        ->assertJson([
            ['id' => 1, 'name' => 'John Doe'],
            ['id' => 2, 'name' => 'Jane Ronaldo'],
        ]);
});

it('test controller search returns filtered results', function () {
    $response = get('api/v2/test?per_page=5&search=John');

    $response->assertStatus(200)
        ->assertJsonCount(2)
        ->assertJsonFragment(['name' => 'John Doe'])
        ->assertJsonFragment(['name' => 'John Smith']);
});

it('test controller pagination works correctly', function () {
    $response = get('api/v2/test?per_page=3&page=2');

    $response->assertStatus(200)
        ->assertJsonCount(3)
        ->assertJsonFragment(['id' => 4])
        ->assertJsonFragment(['id' => 5])
        ->assertJsonFragment(['id' => 6]);
});

it('test controller validates required parameters', function () {
    $response = get('api/v2/test');
    expect($response->status())->toBe(422);
});
