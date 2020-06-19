<?php

namespace PhpSolution\FunctionalTest\Tester;

use PhpSolution\FunctionalTest\Response\ResponseWrapper;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * ApiTester
 */
class ApiTester
{
    use ObjectManagerTrait;

    /**
     * @var int
     */
    protected $expectedStatusCode = Response::HTTP_OK;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $files = [];

    /**
     * @var array
     */
    protected $requestHeaders;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var string
     */
    protected $responseClass;
    /**
     * @var KernelBrowser
     */
    protected $browser;

    /**
     * @param KernelBrowser $browser
     * @param string $responseClass
     */
    public function __construct(
        KernelBrowser $browser,
        string $responseClass = ResponseWrapper::class
    ) {
        $this->browser = $browser;
        $this->responseClass = $responseClass;
        $this->requestHeaders = [];
        $this->guessObjectManagers();
    }

    /**
     * @param int $expectedStatusCode
     *
     * @return self
     */
    public function setExpectedStatusCode(int $expectedStatusCode): ApiTester
    {
        $this->expectedStatusCode = $expectedStatusCode;

        return $this;
    }

    /**
     * @param array $files
     *
     * @return self
     */
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

    /**
     * @return self
     */
    protected function setRequestContentType(): ApiTester
    {
        $this->requestHeaders['CONTENT_TYPE'] = 'application/json';

        return $this;
    }

    /**
     * @return array
     */
    protected function getRequestParameters(): array
    {
        return Request::METHOD_GET === $this->method ? $this->data : [];
    }

    /**
     * @return string|null
     */
    protected function getRequestContent(): ?string
    {
        return Request::METHOD_GET === $this->method ? null : json_encode($this->data, JSON_PRESERVE_ZERO_FRACTION);
    }

    /**
     * @return ApiTester
     */
    protected function assertResponse(): ApiTester
    {
        Assert::assertEquals(
            $this->expectedStatusCode,
            $this->response->getStatusCode(),
            $this->response->getContent()
        );

        return $this;
    }

    /**
     * @param string $path
     * @param array $data
     *
     * @return ResponseWrapper
     */
    public function sendGet(string $path, array $data = []): ResponseWrapper
    {
        return $this->sendRequest(Request::METHOD_GET, $path, $data);
    }

    /**
     * @param string $path
     * @param array $data
     *
     * @return ResponseWrapper
     */
    public function sendPost(string $path, array $data = []): ResponseWrapper
    {
        return $this->sendRequest(Request::METHOD_POST, $path, $data);
    }

    /**
     * @param string $path
     * @param array $data
     *
     * @return ResponseWrapper
     */
    public function sendPut(string $path, array $data = []): ResponseWrapper
    {
        return $this->sendRequest(Request::METHOD_PUT, $path, $data);
    }

    /**
     * @param string $path
     * @param array $data
     *
     * @return ResponseWrapper
     */
    public function sendDelete(string $path, array $data = []): ResponseWrapper
    {
        return $this->sendRequest(Request::METHOD_DELETE, $path, $data);
    }

    /**
     * @param string $path
     * @param array $data
     *
     * @return ResponseWrapper
     */
    public function sendPatch(string $path, array $data = []): ResponseWrapper
    {
        return $this->sendRequest(Request::METHOD_PATCH, $path, $data);
    }

    /**
     * @param string $path
     * @param string $method
     * @param array $data
     *
     * @return ResponseWrapper
     */
    public function sendRequest(string $method, string $path, array $data = []): ResponseWrapper
    {
        $this->method = $method;
        $this->path = $path;
        $this->data = $data;

        $this->setRequestContentType();
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
