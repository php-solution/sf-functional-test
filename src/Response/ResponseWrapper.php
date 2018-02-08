<?php

namespace PhpSolution\FunctionalTest\Response;

use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\Response;

/**
 * ResponseWrapper
 */
class ResponseWrapper
{
    /**
     * @var Response
     */
    private $response;

    /**
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->response->getContent();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        switch ($contentType = $this->response->headers->get('content-type')) {
            case 'application/json':
                return json_decode($this->response->getContent(), true);
            default:
                Assert::fail(sprintf('Unexpected content type: "%s"', $contentType));

                return [];
        }
    }
}
