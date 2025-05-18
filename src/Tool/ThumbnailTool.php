<?php

namespace App\Tool;

use App\Bridge\Replicate\Client;
use PhpLlm\LlmChain\Chain\Toolbox\Attribute\AsTool;

#[AsTool(
    name: 'generate_thumbnail_image',
    description: 'Generate a thumbnail image',
    method: 'generateThumbnailImage',
)]
#[AsTool(
    name: 'generate_ghibli_image',
    description: 'Generate a Ghibli image',
    method: 'generateGhibliImage',
)]
final class ThumbnailTool
{
    private const MATYO91_MODEL_ID = 'matyo91/lora-matyo91:66f13aade1670ece01ae5f0c93955e22decce93f3d60cf6344b096bfbffcf405';
    private const STUDIO_GHIBLI_MODEL_ID = 'karanchawla/studio-ghibli:fd1975a55465d2cf70e5e9aad03e0bb2b13b9f9b715d49a27748fc45797a6ae5';

    public function __construct(
        private Client $client
    ) {
    }
    
    /**
     * @param string  $prompt 
     * @return string 
     */
    public function generateThumbnailImage(string $prompt): string
    {
        try {
            // First, generate the thumbnail with the Matyo91 model
            $response = $this->client->createPrediction(self::MATYO91_MODEL_ID, [
                'model' => 'dev',
                'go_fast' => false,
                'lora_scale' => 1,
                'megapixels' => '1',
                'num_outputs' => 1,
                'aspect_ratio' => '1:1',
                'output_format' => 'webp',
                'guidance_scale' => 3,
                'output_quality' => 80,
                'prompt_strength' => 0.8,
                'extra_lora_scale' => 1,
                'num_inference_steps' => 28,
                'prompt' => $prompt,
            ]);
            
            $data = $response->toArray();
            
            // Check for output in the response
            if (isset($data['output']) && is_array($data['output']) && !empty($data['output'])) {
                $imageUrl = $data['output'][0];
                
                // Now, generate a Studio Ghibli style version using the image URL
                $ghibliResult = $this->createGhibliImage("In the style of Studio Ghibli", $imageUrl);
                
                return sprintf("Created thumbnail for '%s'. Original Image URL: %s\nGhibli Style Image URL: %s", 
                    $prompt, 
                    $imageUrl,
                    $ghibliResult
                );
            }
            
            return sprintf("Successfully processed request for '%s', but no image URL was returned.", $prompt);
        } catch (\Throwable $e) {
            return sprintf("Error generating thumbnail for '%s': %s", $prompt, $e->getMessage());
        }
    }
    
    public function generateGhibliImage(string $prompt): string
    {
        try {
            $imageUrl = $this->createGhibliImage($prompt);
            return sprintf("Created Studio Ghibli style image for '%s'. Image URL: %s", $prompt, $imageUrl);
        } catch (\Throwable $e) {
            return sprintf("Error generating Studio Ghibli style image for '%s': %s", $prompt, $e->getMessage());
        }
    }
    
    /**
     * Creates a Studio Ghibli style image based on a prompt
     * 
     * @param string $prompt The name to use in the prompt
     * @param string|null $imageUrl Optional URL to an existing image for img2img
     * @return string The URL to the generated image
     * @throws \Exception If the image generation fails
     */
    public function createGhibliImage(string $prompt, ?string $imageUrl = null): string
    {
        $input = [
            'prompt' => $prompt,
            'width' => 1024,
            'height' => 1024,
            'num_outputs' => 1,
            'guidance_scale' => 7.5,
            'num_inference_steps' => 50,
            'scheduler' => 'K_EULER',
        ];
        
        // If an image URL is provided, use it for img2img
        if ($imageUrl !== null) {
            $input['image'] = $imageUrl;
            $input['prompt_strength'] = 0.8; // Control how much of the original image to preserve
        }
        
        $response = $this->client->createPrediction(self::STUDIO_GHIBLI_MODEL_ID, $input);
        $data = $response->toArray();
        
        if (!isset($data['output']) || !is_array($data['output']) || empty($data['output'])) {
            throw new \Exception("Failed to generate Studio Ghibli style image");
        }
        
        return $data['output'][0];
    }
} 