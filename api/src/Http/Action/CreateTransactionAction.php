<?php

/**
 * @author Andrey Gordin <andrey@gordin.su>
 */

declare(strict_types=1);

namespace App\Http\Action;

use App\Handler\CreateTransaction\Command;
use App\Handler\CreateTransaction\Handler;
use App\Http\EmptyResponse;
use App\Http\Validator\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class CreateTransactionAction implements RequestHandlerInterface
{
    private Handler $handler;
    private DenormalizerInterface $denormalizer;
    private Validator $validator;

    public function __construct(Handler $handler, DenormalizerInterface $denormalizer, Validator $validator)
    {
        $this->handler = $handler;
        $this->denormalizer = $denormalizer;
        $this->validator = $validator;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = (array)$request->getParsedBody();
        $data['walletId'] = (int)$request->getAttribute('id');

        /** @var Command $command */
        $command = $this->denormalizer->denormalize($data, Command::class);

        $this->validator->validate($command);

        $this->handler->handle($command);

        return new EmptyResponse(201);
    }
}
