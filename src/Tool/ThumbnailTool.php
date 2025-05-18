<?php

namespace App\Tool;

use PhpLlm\LlmChain\Chain\Toolbox\Attribute\AsTool;

#[AsTool('simple_text_tool', 'A simple tool that returns a greeting message')]
final class ThumbnailTool
{
    public function __invoke(string $name = 'world'): string
    {
        return sprintf("Hello, %s! This is a simple text tool response.", $name);
    }
} 