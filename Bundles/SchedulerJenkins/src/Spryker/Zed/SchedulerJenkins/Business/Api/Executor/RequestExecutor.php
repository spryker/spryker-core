<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Api\Executor;

use Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer;
use Psr\Http\Message\RequestInterface;
use RuntimeException;
use Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface;
use Spryker\Zed\SchedulerJenkins\Dependency\Guzzle\SchedulerJenkinsToGuzzleInterface;

class RequestExecutor implements RequestExecutorInterface
{
    protected const AUTH_KEY = 'auth';

    /**
     * @var \Spryker\Zed\SchedulerJenkins\Dependency\Guzzle\SchedulerJenkinsToGuzzleInterface
     */
    protected $client;

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Dependency\Guzzle\SchedulerJenkinsToGuzzleInterface $client
     */
    public function __construct(
        SchedulerJenkinsToGuzzleInterface $client
    ) {
        $this->client = $client;
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface $configurationProvider
     *
     * @return \Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer
     */
    public function execute(
        RequestInterface $request,
        ConfigurationProviderInterface $configurationProvider
    ): SchedulerJenkinsResponseTransfer {
        $requestOptions = $this->getRequestOptions($configurationProvider);

        try {
            $response = $this->client->send($request, $requestOptions);
        } catch (RuntimeException $runtimeException) {
            return $this->createSchedulerJenkinsErrorResponseTransfer($runtimeException->getMessage());
        }

        $payload = $response->getBody()->getContents();

        return $this->createSchedulerJenkinsSuccessResponseTransfer($payload);
    }

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface $configurationProvider
     *
     * @return array
     */
    protected function getRequestOptions(ConfigurationProviderInterface $configurationProvider): array
    {
        $requestOptions = [];

        $jenkinsAuthCredentials = $configurationProvider->getJenkinsAuthCredentials();

        if (count($jenkinsAuthCredentials) === 0) {
            return $requestOptions;
        }

        $requestOptions[static::AUTH_KEY] = $jenkinsAuthCredentials;

        return $requestOptions;
    }

    /**
     * @param string $payload
     *
     * @return \Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer
     */
    protected function createSchedulerJenkinsSuccessResponseTransfer(string $payload): SchedulerJenkinsResponseTransfer
    {
        return (new SchedulerJenkinsResponseTransfer())
            ->setPayload($payload)
            ->setStatus(true);
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer
     */
    protected function createSchedulerJenkinsErrorResponseTransfer(string $message): SchedulerJenkinsResponseTransfer
    {
        return (new SchedulerJenkinsResponseTransfer())
            ->setMessage($message)
            ->setStatus(false);
    }
}
