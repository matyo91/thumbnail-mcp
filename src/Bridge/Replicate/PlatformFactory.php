<?php

declare(strict_types=1);

namespace App\Bridge\Replicate;

use PhpLlm\LlmChain\Bridge\Meta\FinetuneFlux;
use PhpLlm\LlmChain\Bridge\Replicate\Client;
use PhpLlm\LlmChain\Platform;
use Symfony\Component\Clock\Clock;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class PlatformFactory
{
    public static function create(
        #[\SensitiveParameter]
        string $apiKey,
        ?HttpClientInterface $httpClient = null,
    ): Platform {
        return new Platform(
            [new FinetuneFluxModelClient(new Client($httpClient ?? HttpClient::create(), new Clock(), $apiKey))],
            [new FinetuneFluxResponseConverter()],
        );
    }
}
