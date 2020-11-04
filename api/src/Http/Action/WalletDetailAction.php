<?php

/**
 * @author Andrey Gordin <andrey@gordin.su>
 */

declare(strict_types=1);

namespace App\Http\Action;

use App\Handler\WalletDetail\Handler;
use App\Handler\WalletDetail\Query;
use App\Http\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class WalletDetailAction implements RequestHandlerInterface
{
    private Handler $handler;
    private NormalizerInterface $normalizer;

    public function __construct(Handler $handler, NormalizerInterface $normalizer)
    {
        $this->handler = $handler;
        $this->normalizer = $normalizer;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $query = new Query();
        $query->walletId = (int)$request->getAttribute('id');

        $response = $this->handler->handle($query);

        $data = $this->normalizer->normalize($response);

        return new JsonResponse($data);
    }
}
