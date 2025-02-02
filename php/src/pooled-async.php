<?php

use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\Promise;

require_once __DIR__ . '/../vendor/autoload.php';

$client = new Client();

$urls = [];
for ($i = 1; $i <= 1000; $i++) {
    $urls[] = "https://jsonplaceholder.typicode.com/posts/1";
}

// Function to generate request promises
$requests = function ($urls) use ($client) {
    foreach ($urls as $index => $url) {
        yield function () use ($client, $url, $index) {
            if ($index % 100 == 0) {
                $promise = new Promise(function () use (&$promise, $index) {
                    $promise->reject(new RequestException("Simulated Error for $index", new \GuzzleHttp\Psr7\Request('GET', '')));
                });
                return $promise;
            }

            return $client->getAsync($url);
        };
    }
};

$pool = new Pool($client, $requests($urls), [
    'concurrency' => 50, // Process only 2 requests at a time
    'fulfilled' => function ($response, $index) use ($urls) {
        $url = $urls[$index];
        if ($response->getStatusCode() === 200) {
            echo "✅ Success ({$url}): " . substr($response->getBody(), 0, 50) . "...\n";
        } else {
            echo "⚠️  Non-200 Response ({$url}): " . $response->getStatusCode() . "\n";
        }
    },
    'rejected' => function (RequestException $e, $index)  use ($urls) {
        $url = $urls[$index];
        echo "❌ Error ({$url}): " . $e->getMessage() . "\n";
    }
]);

echo "Initial Memory: " . memory_get_usage(true) . " bytes\n";

$start_time = microtime(true);
// Wait for all requests to complete
$promise = $pool->promise();
$promise->wait();
$end_time = microtime(true);
$elapsed_time = number_format($end_time - $start_time, 2);

echo "Peak Memory Usage: " . memory_get_peak_usage(true) . " bytes\n";

echo "time taken $elapsed_time";
