<?php
require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;
use GuzzleHttp\Exception\RequestException;

$client = new Client([
    'base_uri' => 'https://jsonplaceholder.typicode.com/posts/',
    'headers' => [
        'Content-Type' => 'application/json'
    ],
]);

$start_time = microtime(true);
$promises = [];
for ($i = 1; $i <= 100; $i++) {
    echo "ping request $i";
    $promises[] = $client->getAsync("$i");
}
$results = Utils::unwrap($promises);

$end_time = microtime(true);
$elapsed_time = number_format($end_time - $start_time, 2);

foreach ($results as $result) {
    try {
        $response_data = json_decode($result->getBody()->getContents(), true);
        var_dump($response_data['choices'][0]['message']['content']);
    } 
    catch (RequestException $e) {
        var_dump($e->getMessage());
    }
}

var_dump("Elapsed time: $elapsed_time seconds.");