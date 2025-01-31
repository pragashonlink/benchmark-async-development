<?php

use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;
use GuzzleHttp\Pool;

require 'vendor/autoload.php';

$client = new Client();

$urls = [];
for ($i = 1; $i <= 1000; $i++) {
    echo $i;
    $urls[] = "https://jsonplaceholder.typicode.com/posts/1";
}

// Function to generate request promises
$requests = function ($urls) use ($client) {
    foreach ($urls as $url) {
        yield function () use ($client, $url) {
            return $client->getAsync($url);
        };
    }
};

$pool = new Pool($client, $requests($urls), [
    'concurrency' => 50, // Process only 2 requests at a time
    'fulfilled' => function ($response, $index) {
        echo "Response: " . substr($response->getBody(), 0, 50) . "\n";
    },
    'rejected' => function ($reason, $index) {
        echo "Request failed: " . $reason->getMessage() . "\n";
    },
]);

$start_time = microtime(true);
// Wait for all requests to complete
$promise = $pool->promise();
$promise->wait();
$end_time = microtime(true);
$elapsed_time = number_format($end_time - $start_time, 2);

echo "time taken $elapsed_time";
