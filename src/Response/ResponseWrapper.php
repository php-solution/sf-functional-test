<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\Response;

use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\Response;

class ResponseWrapper
{
    private Response $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function getContent(): string
    {
        return $this->response->getContent();
    }

    public function toArray(): array
    {
        switch ($contentType = $this->response->headers->get('content-type')) {
            case 'application/json':
                return json_decode($this->response->getContent(), true);
            default:
                Assert::fail(sprintf('Unexpected content type: "%s"', $contentType));
        }
    }
}
