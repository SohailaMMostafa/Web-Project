<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ActorController extends Controller
{
    private function validateData($date_of_birth): \Illuminate\Validation\Validator
    {
        return Validator::make(
            ['date_of_birth' => $date_of_birth], // Data to validate
            ['date_of_birth' => ['required', 'regex:/^(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/']]
        );
    }

    public function getActorsByBirthdate(Request $request, $date_of_birth)
    {
        $validator = $this->validateData($date_of_birth);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()->getMessages(),
            ], 422);
        }

        $response = Http::withHeaders([
            "X-RapidAPI-Host" => "online-movie-database.p.rapidapi.com",
            "X-RapidAPI-Key" => env('IMDB_API_KEY')
        ])->get("https://online-movie-database.p.rapidapi.com/actors/v2/get-born-today?today=$date_of_birth&first=20");

        if ($response->failed()) {
            Log::channel('stderr')->error("cURL Error #:" . $response->body());

            return response()->json([
                'status' => 'failed',
                'message' => 'Failed to fetch data',
                'errors' => $response->body(),
            ], 500);
        }

        $actorsIds = collect($response->json()['data']['bornToday']['edges'])
            ->pluck('node.id');

        $actorsNames = [];

        foreach ($actorsIds as $id) {
            $response = Http::withHeaders([
                "X-RapidAPI-Host" => "online-movie-database.p.rapidapi.com",
                "X-RapidAPI-Key" => env('IMDB_API_KEY')
            ])->get("https://online-movie-database.p.rapidapi.com/actors/v2/get-bio?nconst=$id");

            if ($response->failed()) {
                Log::channel('stderr')->error("cURL Error #:" . $response->body());
                continue;
            }

            $actorsNames[] = [
                'name' => $response->json()['data']['name']['nameText']['text'],
                'imageUrl' => $response->json()['data']['name']['primaryImage']['url']
            ];
        }

        return response()->json($actorsNames);
    }
}
