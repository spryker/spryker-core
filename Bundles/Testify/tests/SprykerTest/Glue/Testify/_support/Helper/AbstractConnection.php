<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Glue\Testify\Helper;

abstract class AbstractConnection implements Connection
{
    /**
     * @var string
     */
    protected $requestUrl = '';

    /**
     * @var string
     */
    protected $requestMethod = '';

    /**
     * @var array
     */
    protected $requestParameters = [];

    /**
     * @var array
     */
    protected $requestFiles = [];

    /**
     * @var string
     */
    protected $responseBody = '';

    /**
     * @var int
     */
    protected $responseCode = 0;

    /**
     * @var string
     */
    protected $responseContentType = 'application/json';

    /**
     * @inheritdoc
     */
    public function getRequestUrl(): string
    {
        return $this->requestUrl;
    }

    /**
     * @param string $requestUrl
     *
     * @return static
     */
    public function setRequestUrl(string $requestUrl): self
    {
        $this->requestUrl = $requestUrl;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getRequestMethod(): string
    {
        return $this->requestMethod;
    }

    /**
     * @param string $requestMethod
     *
     * @return static
     */
    public function setRequestMethod(string $requestMethod): self
    {
        $this->requestMethod = strtolower($requestMethod);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getRequestParameters(): array
    {
        return $this->requestParameters;
    }

    /**
     * @param array $requestParameters
     *
     * @return static
     */
    public function setRequestParameters(array $requestParameters): self
    {
        $this->requestParameters = $requestParameters;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getRequestFiles(): array
    {
        return $this->requestFiles;
    }

    /**
     * @param array $requestFiles
     *
     * @return static
     */
    public function setRequestFiles(array $requestFiles): self
    {
        $this->requestFiles = $requestFiles;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getResponseBody(): string
    {
        return $this->responseBody;
    }

    /**
     * @param string $responseBody
     *
     * @return static
     */
    public function setResponseBody(string $responseBody): self
    {
        $this->responseBody = $responseBody;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getResponseCode(): int
    {
        return $this->responseCode;
    }

    /**
     * @param int $responseCode
     *
     * @return static
     */
    public function setResponseCode(int $responseCode): self
    {
        $this->responseCode = $responseCode;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getResponseContentType(): string
    {
        return $this->responseContentType;
    }

    /**
     * @param string $responseContentType
     *
     * @return static
     */
    public function setResponseContentType(string $responseContentType): self
    {
        $this->responseContentType = $responseContentType;

        return $this;
    }
}
