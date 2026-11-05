<?php

namespace App\Http\Controllers\Services;

use GuzzleHttp\Client;

class Judge0Service
{
    public function compileCode($code,$lang)
    {

        $client=new Client([
            'base_uri' => 'https://judge0-ce.p.rapidapi.com',
            'headers' => [
                'X-RapidAPI-Key' => env('RAPIDAPI_KEY'),
                                'Content-Type' => 'application/json',
            ],
        ]);
        $response = $client->post('/submissions?base64_encoded=true&wait=true', [
            'json' => [
                'source_code' => $code,
                'language_id' => $lang, // language ID
                'stdin' => '',
                'expected_output' => '',
            ],
        ]);
        $data= json_decode($response->getBody()->getContents(), true);
        return $data;
    }
}
