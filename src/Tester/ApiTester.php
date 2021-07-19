<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\Tester;

use PhpSolution\FunctionalTest\Response\ResponseWrapper;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTester
{
    use ObjectManagerTrait;

    protected int $expectedStatusCode = Response::HTTP_OK;

    protected string $method;

    protected string $path;

    protected array $data;

    protected array $files = [];

    protected array $requestHeaders;

    protected Response $response;

    protected string $responseClass;

    protected KernelBrowser $browser;

    public function __construct(KernelBrowser $browser, string $responseClass = ResponseWrapper::class)
    {
        $this->browser = $browser;
        $this->responseClass = $responseClass;
        $this->requestHeaders = [];
        $this->guessObjectManagers();
        $this->setRequestContentType();
    }

    public function setExpectedStatusCode(int $expectedStatusCode): ApiTester
    {
        $this->expectedStatusCode = $expectedStatusCode;

        return $this;
    }

    public function setFiles(array $files): ApiTester
    {
        $this->files = $files;

        return $this;
    }

    public function authorize(
        string $token,
        string $header = 'Bearer %s',
        string $headerKey = 'HTTP_AUTHORIZATION'
    ): self {
        return $this->withHeader($headerKey, sprintf($header, $token));
    }

    public function withHeader(string $name, string $value): self
    {
        $this->requestHeaders[$name] = $value;

        return $this;
    }

    public function setRequestContentType(string $contentType = 'application/json'): ApiTester
    {
        $this->requestHeaders['CONTENT_TYPE'] = $contentType;

        return $this;
    }

    protected function getRequestParameters(): array
    {
        return Request::METHOD_GET === $this->method ? $this->data : [];
    }

    protected function getRequestContent(): ?string
    {
        return Request::METHOD_GET === $this->method ? null : json_encode($this->data, JSON_PRESERVE_ZERO_FRACTION);
    }

    protected function assertResponse(): ApiTester
    {
        Assert::assertEquals(
            $this->expectedStatusCode,
            $this->response->getStatusCode(),
            (string) $this->response->getContent()
        );

        return $this;
    }

    public function sendGet(string $path, array $data = []): ResponseWrapper
    {
        return $this->sendRequest(Request::METHOD_GET, $path, $data);
    }

    public function sendPost(string $path, array $data = []): ResponseWrapper
    {
        return $this->sendRequest(Request::METHOD_POST, $path, $data);
    }

    public function sendPut(string $path, array $data = []): ResponseWrapper
    {
        return $this->sendRequest(Request::METHOD_PUT, $path, $data);
    }

    public function sendDelete(string $path, array $data = []): ResponseWrapper
    {
        return $this->sendRequest(Request::METHOD_DELETE, $path, $data);
    }

    public function sendPatch(string $path, array $data = []): ResponseWrapper
    {
        return $this->sendRequest(Request::METHOD_PATCH, $path, $data);
    }

    public function sendRequest(string $method, string $path, array $data = []): ResponseWrapper
    {
        $this->method = $method;
        $this->path = $path;
        $this->data = $data;

        $this->browser->request(
            $method,
            $path,
            $this->getRequestParameters(),
            $this->files,
            $this->requestHeaders,
            $this->getRequestContent()
        );
        $this->response = $this->browser->getResponse();
        $this->clearObjectManagers();

        return new $this->responseClass($this->assertResponse()->response);
    }
}
