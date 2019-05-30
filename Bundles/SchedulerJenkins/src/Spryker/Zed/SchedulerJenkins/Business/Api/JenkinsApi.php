<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Api;

use Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer;
use GuzzleHttp\Exception\BadResponseException;
use Spryker\Zed\SchedulerJenkins\Business\Api\Builder\JenkinsResponseBuilderInterface;
use Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface;
use Spryker\Zed\SchedulerJenkins\Dependency\Guzzle\SchedulerJenkinsToGuzzleInterface;

class JenkinsApi implements JenkinsApiInterface
{
    protected const JENKINS_URL_API_CSRF_TOKEN = 'crumbIssuer/api/xml?xpath=concat(//crumbRequestField,":",//crumb)';

    protected const HEADERS_KEY = 'headers';
    protected const BODY_KEY = 'body';
    protected const AUTH_KEY = 'auth';

    protected const SUCCESS_STATUS_CODE = 200;

    protected const REQUEST_GET_METHOD = 'GET';
    protected const REQUEST_POST_METHOD = 'POST';

    /**
     * @var \Spryker\Zed\SchedulerJenkins\Dependency\Guzzle\SchedulerJenkinsToGuzzleInterface
     */
    protected $client;

    /**
     * @var \Spryker\Zed\SchedulerJenkins\Business\Api\Builder\JenkinsResponseBuilderInterface
     */
    protected $jenkinsResponseBuilder;

    /**
     * @var \Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface
     */
    protected $jenkinsConfigurationReader;

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Dependency\Guzzle\SchedulerJenkinsToGuzzleInterface $client
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\Builder\JenkinsResponseBuilderInterface $jenkinsResponseBuilder
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface $jenkinsConfigurationReader
     */
    public function __construct(
        SchedulerJenkinsToGuzzleInterface $client,
        JenkinsResponseBuilderInterface $jenkinsResponseBuilder,
        ConfigurationProviderInterface $jenkinsConfigurationReader
    ) {
        $this->client = $client;
        $this->jenkinsResponseBuilder = $jenkinsResponseBuilder;
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
        return $this->executeRequest(static::REQUEST_GET_METHOD, $idScheduler, $urlPath);
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
        return $this->executeRequest(static::REQUEST_POST_METHOD, $idScheduler, $urlPath);
    }

    /**
     * @param string $method
     * @param string $idScheduler
     * @param string $urlPath
     * @param string $body
     *
     * @return \Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer
     */
    protected function executeRequest(
        string $method,
        string $idScheduler,
        string $urlPath,
        string $body = ''
    ): SchedulerJenkinsResponseTransfer {
        try {
            $baseUrl = $this->jenkinsConfigurationReader->getJenkinsBaseUrlBySchedulerId($idScheduler, $urlPath);
            $requestOptions = $this->getRequestOptions($idScheduler, $body);
            $response = $this->client->request($method, $baseUrl, $requestOptions);
        } catch (BadResponseException $badResponseException) {
            return $this->jenkinsResponseBuilder
                ->withMessage($badResponseException->getMessage())
                ->withStatus(false)
                ->build();
        }

        return $this->jenkinsResponseBuilder
            ->withPayload($response->getBody()->getContents())
            ->withStatus($response->getStatusCode() === static::SUCCESS_STATUS_CODE)
            ->build();
    }

    /**
     * @param string $schedulerId
     * @param string $body
     *
     * @return array
     */
    protected function getRequestOptions(string $schedulerId, string $body = ''): array
    {
        $requestOptions = [
            static::HEADERS_KEY => $this->getHeaders($body),
            static::BODY_KEY => $body,
            static::AUTH_KEY => $this->jenkinsConfigurationReader->getJenkinsAuthCredentials($schedulerId),
        ];

        return $requestOptions;
    }

    /**
     * @param string $xmlTemplate
     *
     * @return array
     */
    protected function getHeaders(string $xmlTemplate = ''): array
    {
        $httpHeader = [];

        if ($xmlTemplate) {
            $httpHeader = [
                'Content-Type' => 'text/xml; charset=UTF8',
            ];
        }

        if ($this->jenkinsConfigurationReader->isJenkinsCsrfProtectionEnabled()) {
            $httpHeader[] = $this->client->request(static::REQUEST_GET_METHOD, static::JENKINS_URL_API_CSRF_TOKEN);
        }

        return $httpHeader;
    }
}
