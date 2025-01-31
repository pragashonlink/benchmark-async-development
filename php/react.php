<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use \GuzzleHttp\Promise\Utils;
use React\EventLoop\Factory;

$loop = Factory::create(); // Create an event loop
$client = new Client();

// Define an array of URLs to send requests to
$urls = [
    'http://localhost:3000/health',
    'http://localhost:3000/health',
    'http://localhost:3000/health',
    'http://localhost:3000/health',
    'http://localhost:3000/health'
];

// Create an array to hold promises for each request
$promises = [];

// Loop through each URL and create a promise for the GET request
foreach ($urls as $url) {
    $promises[] = $client->getAsync($url)
        ->then(
            function ($response) use ($url) {
                echo "Response from $url: " . $response->getStatusCode() . "\n";
                echo "Body: " . $response->getBody() . "\n";
            },
            function ($exception) use ($url) {
                echo "Error for $url: " . $exception->getMessage() . "\n";
            }
        );
}

$start = microtime(true);
// Wait for all promises to settle (run concurrently)
Utils::settle($promises)->wait();
$end = microtime(true);
echo "Time taken: " . ($end - $start) . " seconds";