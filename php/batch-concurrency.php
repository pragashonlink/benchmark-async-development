<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;

// List of 10,000 API URLs
$apiUrls = []; // Populate this with 10,000 API URLs
for ($i = 1; $i <= 10000; $i++) {
    $apiUrls[] = "https://api.example.com/healthcheck/$i";
}

// Configure concurrency and batching
$client = new Client(['timeout' => 5]); // Set timeout for each API call
$batchSize = 50; // Number of concurrent requests per batch
$totalApis = count($apiUrls);
$batches = array_chunk($apiUrls, $batchSize);

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

echo "Health check completed for all APIs!";
