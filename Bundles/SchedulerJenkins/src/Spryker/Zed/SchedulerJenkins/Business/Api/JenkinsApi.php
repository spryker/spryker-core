<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Api;

use Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Psr\Http\Message\RequestInterface;
use RuntimeException;
use Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface;
use Spryker\Zed\SchedulerJenkins\Dependency\Guzzle\SchedulerJenkinsToGuzzleInterface;

class JenkinsApi implements JenkinsApiInterface
{
    protected const JENKINS_URL_API_CSRF_TOKEN = 'crumbIssuer/api/xml?xpath=string(//crumb)';

    protected const HEADERS_KEY = 'headers';
    protected const BODY_KEY = 'body';
    protected const AUTH_KEY = 'auth';
    protected const CRUMB_KEY = '.crumb';

    protected const SUCCESS_STATUS_CODE = 200;

    protected const REQUEST_GET_METHOD = 'GET';
    protected const REQUEST_POST_METHOD = 'POST';

    /**
     * @var \Spryker\Zed\SchedulerJenkins\Dependency\Guzzle\SchedulerJenkinsToGuzzleInterface
     */
    protected $client;

    /**
     * @var \Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface
     */
    protected $jenkinsConfigurationReader;

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Dependency\Guzzle\SchedulerJenkinsToGuzzleInterface $client
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface $jenkinsConfigurationReader
     */
    public function __construct(
        SchedulerJenkinsToGuzzleInterface $client,
        ConfigurationProviderInterface $jenkinsConfigurationReader
    ) {
        $this->client = $client;
        $this->jenkinsConfigurationReader = $jenkinsConfigurationReader;
    }

    /**
     * @param string $idScheduler
     * @param string $urlPath
     *
     * @return \Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer
     */
    public function executeGetRequest(string $idScheduler, string $urlPath): SchedulerJenkinsResponseTransfer
    {
        $request = $this->createGuzzleRequest(static::REQUEST_GET_METHOD, $idScheduler, $urlPath);

        return $this->executeRequest($request, $idScheduler);
    }

    /**
     * @param string $idScheduler
     * @param string $urlPath
     * @param string $body
     *
     * @return \Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer
     */
    public function executePostRequest(string $idScheduler, string $urlPath, string $body = ''): SchedulerJenkinsResponseTransfer
    {
        $request = $this->createGuzzleRequest(static::REQUEST_POST_METHOD, $idScheduler, $urlPath, $body);

        return $this->executeRequest($request, $idScheduler);
    }

    /**
     * @param string $requestMethod
     * @param string $idScheduler
     * @param string $urlPath
     * @param string $body
     *
     * @return \Psr\Http\Message\RequestInterface
     */
    protected function createGuzzleRequest(string $requestMethod, string $idScheduler, string $urlPath, string $body = ''): RequestInterface
    {
        $baseUrl = $this->jenkinsConfigurationReader->getJenkinsBaseUrlBySchedulerId($idScheduler, $urlPath);
        $headers = $this->getHeaders($idScheduler);

        $request = new Psr7Request(
            $requestMethod,
            $baseUrl,
            $headers,
            $body
        );

        return $request;
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param string $idScheduler
     *
     * @return \Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer
     */
    protected function executeRequest(
        RequestInterface $request,
        string $idScheduler
    ): SchedulerJenkinsResponseTransfer {
        try {
            $requestOptions = $this->getRequestOptions($idScheduler);
            $response = $this->client->send($request, $requestOptions);
        } catch (RuntimeException $runtimeException) {
            return $this->createSchedulerJenkinsErrorResponseTransfer($runtimeException->getMessage(), false);
        }

        $payload = $response->getBody()->getContents();
        $status = $response->getStatusCode() === static::SUCCESS_STATUS_CODE;

        return $this->createSchedulerJenkinsSuccessResponseTransfer($payload, $status);
    }

    /**
     * @param string $payload
     * @param bool $status
     *
     * @return \Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer
     */
    protected function createSchedulerJenkinsSuccessResponseTransfer(string $payload, bool $status): SchedulerJenkinsResponseTransfer
    {
        return (new SchedulerJenkinsResponseTransfer())
            ->setPayload($payload)
            ->setStatus($status);
    }

    /**
     * @param string $message
     * @param bool $status
     *
     * @return \Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer
     */
    protected function createSchedulerJenkinsErrorResponseTransfer(string $message, bool $status): SchedulerJenkinsResponseTransfer
    {
        return (new SchedulerJenkinsResponseTransfer())
            ->setMessage($message)
            ->setStatus($status);
    }

    /**
     * @param string $idScheduler
     *
     * @return array
     */
    protected function getHeaders(string $idScheduler): array
    {
        $httpHeader = [
            'Content-Type' => 'text/xml; charset=UTF8',
        ];

        if ($this->jenkinsConfigurationReader->isJenkinsCsrfProtectionEnabled()) {
            $csrfProtectionToken = $this->getCsrfToken($idScheduler);
            $httpHeader[static::CRUMB_KEY] = $csrfProtectionToken;
        }

        return $httpHeader;
    }

    /**
     * @param string $idScheduler
     *
     * @return string
     */
    protected function getCsrfToken(string $idScheduler): string
    {
        $baseUrl = $this->jenkinsConfigurationReader->getJenkinsBaseUrlBySchedulerId($idScheduler, static::JENKINS_URL_API_CSRF_TOKEN);
        $response = $this->client->request(static::REQUEST_GET_METHOD, $baseUrl);

        return $response->getBody()->getContents();
    }

    /**
     * @param string $idScheduler
     *
     * @return array
     */
    protected function getRequestOptions(string $idScheduler): array
    {
        $requestOptions = [];

        $jenkinsAuthCredentials = $this->jenkinsConfigurationReader->getJenkinsAuthCredentials($idScheduler);

        if (count($jenkinsAuthCredentials) === 0) {
            $requestOptions[static::AUTH_KEY] = $jenkinsAuthCredentials;
        }

        return $requestOptions;
    }
}
