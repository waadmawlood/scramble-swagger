<?php

namespace Waad\ScrambleSwagger\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class GenerateScrambleSwagger extends Command
{
    protected $signature = 'scramble:swagger:auto';

    protected $description = 'Generate Scramble Swagger documentation and copy paths to components';

    public function handle()
    {
        $path = public_path('scramble-swagger/doc.json');
        Artisan::call('scramble:export', ['--path' => $path]);

        $this->copyPathsToComponents($path);

        return Command::SUCCESS;
    }

    private function copyPathsToComponents($filePath)
    {
        $jsonContent = file_get_contents($filePath);
        $swaggerData = json_decode($jsonContent, true);

        if ($swaggerData === null) {
            throw new \Exception("Error decoding JSON from file: $filePath");
        }

        if (isset($swaggerData['paths'])) {
            $swaggerData['components']['paths'] = $swaggerData['paths'];
        } else {
            throw new \Exception("No 'paths' key found in the JSON.");
        }

        file_put_contents($filePath, json_encode($swaggerData));

        return $filePath;
    }
}
