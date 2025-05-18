<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Tool\ThumbnailTool;
use PhpLlm\LlmChain\Bridge\Replicate\PlatformFactory;

// Load environment variables - make sure REPLICATE_API_KEY is set in your environment
$apiKey = $_ENV['REPLICATE_API_KEY'] ?? getenv('REPLICATE_API_KEY');

if (!$apiKey) {
    die("Error: REPLICATE_API_KEY is not set. Please set the environment variable.\n");
}

// Create the platform with the Replicate factory
$platform = PlatformFactory::create($apiKey);

// Create the thumbnail tool with the actual platform
$thumbnailTool = new ThumbnailTool($platform);

// Run the tool with a sample name
try {
    echo "Generating thumbnail...\n";
    $result = $thumbnailTool('John Doe');
    echo "Success: " . $result . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 