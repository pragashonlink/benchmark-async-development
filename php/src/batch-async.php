<?php
require_once __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;

$client = new Client();

$start_time = microtime(true);
$urls = [];
for ($i = 1; $i <= 1000; $i++) {
    $urls[] = "https://jsonplaceholder.typicode.com/posts/1";
}

// Configure concurrency and batching
$client = new Client(['timeout' => 5]); // Set timeout for each API call
$batchSize = 50; // Number of concurrent requests per batch
$totalApis = count($urls);
$batches = array_chunk($urls, $batchSize);

foreach ($batches as $index => $batch) {
    echo "Processing batch " . ($index + 1) . "/" . count($batches) . "\n";

    $promises = [];
    foreach ($batch as $url) {
        $promises[$url] = $client->getAsync($url);
    }

    // Execute the batch concurrently
    $responses = Utils::settle($promises)->wait();

    // Process results
    foreach ($responses as $url => $result) {
        if ($result['state'] === 'fulfilled') {
            echo "Success: $url - " . $result['value']->getStatusCode() . "\n";
        } else {
            echo "Failed: $url - " . $result['reason']->getMessage() . "\n";
        }
    }

    // Optional: Add a short delay between batches to prevent overwhelming the system
    usleep(500000); // 500ms delay
}
$end_time = microtime(true);
$elapsed_time = number_format($end_time - $start_time, 2);

echo "Health check completed for all APIs! It took $elapsed_time";
