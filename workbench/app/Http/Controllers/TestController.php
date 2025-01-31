<?php

namespace Workbench\App\Http\Controllers;

use Illuminate\Http\Request;

class TestController
{
    const DATA = [
        ['id' => 1, 'name' => 'John Doe'],
        ['id' => 2, 'name' => 'Jane Ronaldo'],
        ['id' => 3, 'name' => 'John Smith'],
        ['id' => 4, 'name' => 'Jane Messi'],
        ['id' => 5, 'name' => 'Mario Khabib'],
        ['id' => 6, 'name' => 'Luigi Jordin'],
        ['id' => 7, 'name' => 'Mario Sami'],
        ['id' => 8, 'name' => 'Roni Maikel'],
        ['id' => 9, 'name' => 'Sarah Connor'],
        ['id' => 10, 'name' => 'Tony Stark'],
        ['id' => 11, 'name' => 'Bruce Wayne'],
        ['id' => 12, 'name' => 'Peter Parker'],
        ['id' => 13, 'name' => 'Clark Kent'],
    ];

    public function index(Request $request)
    {
        try {
            $request->validate([
                'per_page' => 'required|integer',
                'page' => 'nullable|integer',
                'search' => 'nullable|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        }

        $page = $request->page ?? 1;
        $perPage = $request->per_page ?? 5;
        $search = $request->search ?? null;

        $data = collect(self::DATA);

        if ($search) {
            $data = $data->filter(function ($item) use ($search) {
                return str_contains($item['name'], $search);
            });
        }

        return $data->skip(($page - 1) * $perPage)->take($perPage)->toArray();
    }
}
