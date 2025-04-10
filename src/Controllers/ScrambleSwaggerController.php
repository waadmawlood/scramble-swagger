<?php

namespace Waad\ScrambleSwagger\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Waad\ScrambleSwagger\Commands\GenerateScrambleSwagger;

class ScrambleSwaggerController
{
    public function __construct()
    {
        if (! config('scramble-swagger.enable')) {
            abort(404);
        }
    }

    public function show()
    {
        $response = $this->responseJson();
        $versions = $this->getApiVersions();

        return view('scramble-swagger::docs', compact('response', 'versions'));
    }

    public function responseJson()
    {
        $status = Artisan::call(GenerateScrambleSwagger::class);
        if ($status !== 0) {
            throw new \Exception('Failed to generate Swagger documentation');
        }

        $jsonContent = Storage::disk('local')->get('doc.json');
        if (empty($jsonContent)) {
            return [];
        }

        $data = json_decode($jsonContent, true);
        if (empty($data)) {
            return [];
        }

        return request()->filled('version') ? $this->filterByVersion($data) : $data;
    }

    private function getApiVersions()
    {
        $config = config('scramble-swagger');
        $configVersions = $config['versions'];

        if (empty($configVersions)) {
            return ['all'];
        }

        $versions = [];
        foreach ($configVersions as $version) {
            $versions[] = [
                'url' => url(sprintf('%s/json?version=%s', $config['url'], $version)),
                'name' => $version,
            ];
        }

        return [
            'default' => $config['default_version'] ?? $configVersions[0] ?? 'all',
            'versions' => $versions,
        ];
    }

    public function filterByVersion($dataJson)
    {
        $searchTerm = request()->version;
        if ($searchTerm === 'all' || is_null($searchTerm)) {
            return $dataJson;
        }

        $paths = [];
        $tags = [];
        foreach ($dataJson['components']['paths'] as $key => $path) {
            if (! str_contains($key, $searchTerm)) {
                continue;
            }

            $paths[$key] = $dataJson['components']['paths'][$key];
            foreach ($path as $path_value) {
                $tags[] = $path_value['tags'][0] ?? '';
            }
        }

        $dataJson['components']['paths'] = $dataJson['paths'] = $paths;
        $dataJson['components']['tags'] = $dataJson['tags'] = array_unique(array_filter($tags));

        return $dataJson;
    }
}
