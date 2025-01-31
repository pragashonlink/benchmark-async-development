<?php
require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;

$client = new Client();

$ping = function($client) {
    return $client->request('GET', 'https://jsonplaceholder.typicode.com/posts/1', [
        'headers' => [
            'Accept' => 'application/json',
        ],
    ])->getBody();
};

$start_time = microtime(true);
for ($i = 1; $i <= 1000; $i++) {
    echo "ping request $i";
    $ping($client);
}
$end_time = microtime(true);
$elapsed_time = number_format($end_time - $start_time, 2);

echo "time taken $elapsed_time";