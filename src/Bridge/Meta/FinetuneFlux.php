<?php

declare(strict_types=1);

namespace App\Bridge\Meta;

use PhpLlm\LlmChain\Model\Model;

final readonly class FinetuneFlux implements Model
{
    /**
     * @param array<string, mixed> $options
     */
    public function __construct(
        private string $version,
        private array $options = [],
    ) {
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
