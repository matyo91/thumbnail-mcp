<?php

declare(strict_types=1);

namespace App\Bridge\Replicate;

use App\Bridge\Meta\FinetuneFlux;
use App\Bridge\Meta\FinetuneFluxPromptConverter;
use PhpLlm\LlmChain\Bridge\Replicate\Client;
use PhpLlm\LlmChain\Model\Message\MessageBagInterface;
use PhpLlm\LlmChain\Model\Message\SystemMessage;
use PhpLlm\LlmChain\Model\Model;
use PhpLlm\LlmChain\Platform\ModelClient;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Webmozart\Assert\Assert;

final readonly class FinetuneFluxModelClient implements ModelClient
{
    public function __construct(
        private Client $client,
        private FinetuneFluxPromptConverter $promptConverter = new FinetuneFluxPromptConverter(),
    ) {
    }

    public function supports(Model $model, object|array|string $input): bool
    {
        return $model instanceof FinetuneFlux && $input instanceof MessageBagInterface;
    }

    public function request(Model $model, object|array|string $input, array $options = []): ResponseInterface
    {
        Assert::isInstanceOf($model, FinetuneFlux::class);
        Assert::isInstanceOf($input, MessageBagInterface::class);

        return $this->client->request($model->getVersion(), 'predictions', [
            'system' => $this->promptConverter->convertMessage($input->getSystemMessage() ?? new SystemMessage('')),
            'prompt' => $this->promptConverter->convertToPrompt($input->withoutSystemMessage()),
        ]);
    }
}
