<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business\Api;

use Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer;
use Psr\Http\Message\RequestInterface;
use Spryker\Zed\SchedulerJenkins\Business\Api\Builder\RequestBuilderInterface;
use Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface;
use Spryker\Zed\SchedulerJenkins\Business\Api\Exception\InvalidJenkinsConfiguration;
use Spryker\Zed\SchedulerJenkins\Business\Api\Executor\RequestExecutorInterface;

class JenkinsApi implements JenkinsApiInterface
{
    protected const JENKINS_URL_API_CSRF_TOKEN = 'crumbIssuer/api/xml?xpath=string(//crumb)';

    protected const GET_JOBS_URL = 'api/json/jobs?pretty=true&tree=jobs[name]';
    protected const CREATE_JOB_URL = 'createItem?name=%s';
    protected const DELETE_JOB_URL = 'job/%s/doDelete';
    protected const UPDATE_JOB_URL = 'job/%s/config.xml';
    protected const ENABLE_JOB_URL = 'job/%s/enable';
    protected const DISABLE_JOB_URL = 'job/%s/disable';

    protected const REQUEST_GET_METHOD = 'GET';
    protected const REQUEST_POST_METHOD = 'POST';

    protected const AUTH_KEY = 'auth';
    protected const CRUMB_KEY = '.crumb';

    /**
     * @var \Spryker\Zed\SchedulerJenkins\Business\Api\Builder\RequestBuilderInterface
     */
    protected $requestBuilder;

    /**
     * @var \Spryker\Zed\SchedulerJenkins\Business\Api\Executor\RequestExecutorInterface
     */
    protected $requestExecutor;

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\Builder\RequestBuilderInterface $requestBuilder
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\Executor\RequestExecutorInterface $requestExecutor
     */
    public function __construct(
        RequestBuilderInterface $requestBuilder,
        RequestExecutorInterface $requestExecutor
    ) {
        $this->requestBuilder = $requestBuilder;
        $this->requestExecutor = $requestExecutor;
    }

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface $configurationProvider
     *
     * @return \Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer
     */
    public function getJobs(ConfigurationProviderInterface $configurationProvider): SchedulerJenkinsResponseTransfer
    {
        $request = $this->requestBuilder->buildPsrRequest(
            static::REQUEST_GET_METHOD,
            $configurationProvider,
            static::GET_JOBS_URL
        );

        $request = $this->extendRequestWithCsrfToken($request, $configurationProvider);

        return $this->requestExecutor->execute($request, $configurationProvider);
    }

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface $configurationProvider
     * @param string $jobName
     * @param string $jobXmlTemplate
     *
     * @return \Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer
     */
    public function updateJob(ConfigurationProviderInterface $configurationProvider, string $jobName, string $jobXmlTemplate): SchedulerJenkinsResponseTransfer
    {
        $request = $this->requestBuilder->buildPsrRequest(
            static::REQUEST_POST_METHOD,
            $configurationProvider,
            sprintf(static::UPDATE_JOB_URL, $jobName),
            $jobXmlTemplate
        );

        $request = $this->extendRequestWithCsrfToken($request, $configurationProvider);

        return $this->requestExecutor->execute($request, $configurationProvider);
    }

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface $configurationProvider
     * @param string $jobName
     * @param string $jobXmlTemplate
     *
     * @return \Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer
     */
    public function createJob(ConfigurationProviderInterface $configurationProvider, string $jobName, string $jobXmlTemplate): SchedulerJenkinsResponseTransfer
    {
        $request = $this->requestBuilder->buildPsrRequest(
            static::REQUEST_POST_METHOD,
            $configurationProvider,
            sprintf(static::CREATE_JOB_URL, $jobName),
            $jobXmlTemplate
        );

        $request = $this->extendRequestWithCsrfToken($request, $configurationProvider);

        return $this->requestExecutor->execute($request, $configurationProvider);
    }

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface $configurationProvider
     * @param string $jobName
     *
     * @return \Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer
     */
    public function deleteJob(ConfigurationProviderInterface $configurationProvider, string $jobName): SchedulerJenkinsResponseTransfer
    {
        $request = $this->requestBuilder->buildPsrRequest(
            static::REQUEST_POST_METHOD,
            $configurationProvider,
            sprintf(static::DELETE_JOB_URL, $jobName)
        );

        $request = $this->extendRequestWithCsrfToken($request, $configurationProvider);

        return $this->requestExecutor->execute($request, $configurationProvider);
    }

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface $configurationProvider
     * @param string $jobName
     *
     * @return \Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer
     */
    public function enableJob(ConfigurationProviderInterface $configurationProvider, string $jobName): SchedulerJenkinsResponseTransfer
    {
        $request = $this->requestBuilder->buildPsrRequest(
            static::REQUEST_POST_METHOD,
            $configurationProvider,
            sprintf(static::ENABLE_JOB_URL, $jobName)
        );

        $request = $this->extendRequestWithCsrfToken($request, $configurationProvider);

        return $this->requestExecutor->execute($request, $configurationProvider);
    }

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface $configurationProvider
     * @param string $jobName
     *
     * @return \Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer
     */
    public function disableJob(ConfigurationProviderInterface $configurationProvider, string $jobName): SchedulerJenkinsResponseTransfer
    {
        $request = $this->requestBuilder->buildPsrRequest(
            static::REQUEST_POST_METHOD,
            $configurationProvider,
            sprintf(static::DISABLE_JOB_URL, $jobName)
        );

        $request = $this->extendRequestWithCsrfToken($request, $configurationProvider);

        return $this->requestExecutor->execute($request, $configurationProvider);
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface $configurationProvider
     *
     * @return \Psr\Http\Message\RequestInterface
     */
    protected function extendRequestWithCsrfToken(RequestInterface $request, ConfigurationProviderInterface $configurationProvider): RequestInterface
    {
        if ($configurationProvider->isJenkinsCsrfProtectionEnabled()) {
            $csrfProtectionToken = $this->getCsrfToken($configurationProvider);
            $request = $request->withHeader(static::CRUMB_KEY, $csrfProtectionToken);
        }

        return $request;
    }

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface $configurationProvider
     *
     * @throws \Spryker\Zed\SchedulerJenkins\Business\Api\Exception\InvalidJenkinsConfiguration
     *
     * @return string
     */
    protected function getCsrfToken(ConfigurationProviderInterface $configurationProvider): string
    {
        $request = $this->requestBuilder->buildPsrRequest(
            static::REQUEST_GET_METHOD,
            $configurationProvider,
            static::JENKINS_URL_API_CSRF_TOKEN
        );

        $responseTransfer = $this->requestExecutor->execute($request, $configurationProvider);
        $payload = $responseTransfer->getPayload();

        if ($payload === null) {
            throw new InvalidJenkinsConfiguration('Cannot generate CSRF token. Please check that CSRF protection is enabled on Jenkins server.');
        }

        return $payload;
    }
}
