<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Tool\ThumbnailTool;
use PhpLlm\LlmChain\Model\Model;
use PhpLlm\LlmChain\Platform;
use PhpLlm\LlmChain\Model\Response\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseInterface as HttpResponse;

// Create a mock implementation for the Platform
class TestPlatform extends Platform
{
    public function __construct()
    {
        // Override constructor to avoid dependencies
    }

    public function request(Model $model, array|string|object $input, array $options = []): ResponseInterface
    {
        echo "Model ID: " . $model->getId() . PHP_EOL;
        echo "Input options: " . json_encode($input, JSON_PRETTY_PRINT) . PHP_EOL;
        
        return new class() implements ResponseInterface {
            public function getContent()
            {
                // Mock response with a fake image URL
                return ['https://replicate.delivery/example/thumbnail-image.webp'];
            }
        };
    }
}

// Create the tool with our mock platform
$platform = new TestPlatform();
$thumbnailTool = new ThumbnailTool($platform);

// Test the tool with a name
$result = $thumbnailTool('John Doe');

// Output the result
echo "Result: " . $result . PHP_EOL; 