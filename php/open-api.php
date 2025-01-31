<?php
require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Exception\RequestException;

$items = [
  '1',
  '2',
  '3',
  '4',
  '5',
];

$client = new Client([
  'base_uri' => 'https://jsonplaceholder.typicode.com/posts/',
  'headers' => [
    'Content-Type' => 'application/json'
  ],
]);

$start_time = microtime(true);

$promises = [];
for ($i = 1; $i <= 300; $i++) {
    echo $i;
    $promises[] = $client->getAsync("1");
}

// Wait for all the requests to complete.
$results = Promise\Utils::unwrap($promises);

$end_time = microtime(true);
$elapsed_time = number_format($end_time - $start_time, 2);

// Output the results after all promises have been resolved.
foreach ($results as $result) {
  try {
    $response_data = json_decode($result->getBody()->getContents(), true);
    var_dump($response_data);
  } 
  catch (RequestException $e) {
    var_dump($e->getMessage());
  }
}

var_dump("Elapsed time: $elapsed_time seconds.");