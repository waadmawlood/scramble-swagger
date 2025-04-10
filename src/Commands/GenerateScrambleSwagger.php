<?php

namespace Waad\ScrambleSwagger\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class GenerateScrambleSwagger extends Command
{
    protected $signature = 'scramble:swagger:auto';

    protected $description = 'Generate Scramble Swagger documentation and copy paths to components';

    public function handle()
    {
        $fileName = 'doc.json';
        $path = Storage::disk('local')->path($fileName);
        Artisan::call('scramble:export', ['--path' => $path]);

        $this->copyPathsToComponents($fileName);

        return Command::SUCCESS;
    }

    private function copyPathsToComponents($fileName)
    {
        $jsonContent = Storage::disk('local')->get($fileName);
        $swaggerData = json_decode($jsonContent, true);

        if ($swaggerData === null) {
            throw new \Exception("Error decoding JSON from file: $fileName");
        }

        if (isset($swaggerData['paths'])) {
            $swaggerData['components']['paths'] = $swaggerData['paths'];
        } else {
            throw new \Exception("No 'paths' key found in the JSON.");
        }

        Storage::disk('local')->put($fileName, json_encode($swaggerData));

        return $fileName;
    }
}
