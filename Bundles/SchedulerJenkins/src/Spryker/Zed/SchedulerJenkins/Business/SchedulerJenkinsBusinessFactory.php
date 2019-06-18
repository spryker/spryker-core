<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SchedulerJenkins\Business\Api\Builder\RequestBuilder;
use Spryker\Zed\SchedulerJenkins\Business\Api\Builder\RequestBuilderInterface;
use Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface;
use Spryker\Zed\SchedulerJenkins\Business\Api\Executor\RequestExecutor;
use Spryker\Zed\SchedulerJenkins\Business\Api\Executor\RequestExecutorInterface;
use Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApi;
use Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApiInterface;
use Spryker\Zed\SchedulerJenkins\Business\Executor\CreateExecutor;
use Spryker\Zed\SchedulerJenkins\Business\Executor\DeleteExecutor;
use Spryker\Zed\SchedulerJenkins\Business\Executor\DisableExecutor;
use Spryker\Zed\SchedulerJenkins\Business\Executor\EnableExecutor;
use Spryker\Zed\SchedulerJenkins\Business\Executor\ExecutorInterface;
use Spryker\Zed\SchedulerJenkins\Business\Executor\NullExecutor;
use Spryker\Zed\SchedulerJenkins\Business\Executor\UpdateExecutor;
use Spryker\Zed\SchedulerJenkins\Business\Processor\Builder\ConfigurationProviderBuilder;
use Spryker\Zed\SchedulerJenkins\Business\Processor\Builder\ConfigurationProviderBuilderInterface;
use Spryker\Zed\SchedulerJenkins\Business\Processor\Configuration\ConfigurationProvider;
use Spryker\Zed\SchedulerJenkins\Business\Processor\ScheduleProcessor;
use Spryker\Zed\SchedulerJenkins\Business\Processor\ScheduleProcessorInterface;
use Spryker\Zed\SchedulerJenkins\Business\Processor\Strategy\ExecutionStrategyBuilder;
use Spryker\Zed\SchedulerJenkins\Business\Processor\Strategy\ExecutionStrategyBuilderInterface;
use Spryker\Zed\SchedulerJenkins\Business\TemplateGenerator\JenkinsJobTemplateGeneratorInterface;
use Spryker\Zed\SchedulerJenkins\Business\TemplateGenerator\XmlJenkinsJobTemplateGenerator;
use Spryker\Zed\SchedulerJenkins\Dependency\Guzzle\SchedulerJenkinsToGuzzleInterface;
use Spryker\Zed\SchedulerJenkins\Dependency\Service\SchedulerJenkinsToUtilEncodingServiceInterface;
use Spryker\Zed\SchedulerJenkins\Dependency\TwigEnvironment\SchedulerJenkinsToTwigEnvironmentInterface;
use Spryker\Zed\SchedulerJenkins\SchedulerJenkinsDependencyProvider;

/**
 * @method \Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig getConfig()
 */
class SchedulerJenkinsBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApiInterface
     */
    public function createJenkinsApi(): JenkinsApiInterface
    {
        return new JenkinsApi(
            $this->createJenkinsApiRequestBuilder(),
            $this->createJenkinsApiRequestExecutor()
        );
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\Processor\ScheduleProcessorInterface
     */
    public function createSchedulerJenkinsSetup(): ScheduleProcessorInterface
    {
        return new ScheduleProcessor(
            $this->createExecutionStrategyBuilder(
                $this->createUpdateExecutor(),
                $this->createCreateExecutor()
            ),
            $this->createConfigurationProviderBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\Processor\ScheduleProcessorInterface
     */
    public function createSchedulerJenkinsClean(): ScheduleProcessorInterface
    {
        return new ScheduleProcessor(
            $this->createExecutionStrategyBuilder(
                $this->createDeleteExecutor(),
                $this->createNullExecutor()
            ),
            $this->createConfigurationProviderBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\Processor\ScheduleProcessorInterface
     */
    public function createSchedulerJenkinsEnable(): ScheduleProcessorInterface
    {
        return new ScheduleProcessor(
            $this->createExecutionStrategyBuilder(
                $this->createEnableExecutor(),
                $this->createNullExecutor()
            ),
            $this->createConfigurationProviderBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\Processor\ScheduleProcessorInterface
     */
    public function createSchedulerJenkinsDisable(): ScheduleProcessorInterface
    {
        return new ScheduleProcessor(
            $this->createExecutionStrategyBuilder(
                $this->createDisableExecutor(),
                $this->createNullExecutor()
            ),
            $this->createConfigurationProviderBuilder()
        );
    }

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Executor\ExecutorInterface $executorForExistingJob
     * @param \Spryker\Zed\SchedulerJenkins\Business\Executor\ExecutorInterface $executorForAbsentJob
     *
     * @return \Spryker\Zed\SchedulerJenkins\Business\Processor\Strategy\ExecutionStrategyBuilderInterface
     */
    public function createExecutionStrategyBuilder(
        ExecutorInterface $executorForExistingJob,
        ExecutorInterface $executorForAbsentJob
    ): ExecutionStrategyBuilderInterface {
        return new ExecutionStrategyBuilder(
            $this->createJenkinsApi(),
            $this->getUtilEncodingService(),
            $executorForExistingJob,
            $executorForAbsentJob
        );
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\TemplateGenerator\JenkinsJobTemplateGeneratorInterface
     */
    public function createXmkJenkinsJobTemplateGenerator(): JenkinsJobTemplateGeneratorInterface
    {
        return new XmlJenkinsJobTemplateGenerator(
            $this->getTwigEnvironment(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\Executor\ExecutorInterface
     */
    public function createCreateExecutor(): ExecutorInterface
    {
        return new CreateExecutor(
            $this->createJenkinsApi(),
            $this->createXmkJenkinsJobTemplateGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\Executor\ExecutorInterface
     */
    public function createUpdateExecutor(): ExecutorInterface
    {
        return new UpdateExecutor(
            $this->createJenkinsApi(),
            $this->createXmkJenkinsJobTemplateGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\Executor\ExecutorInterface
     */
    public function createDeleteExecutor(): ExecutorInterface
    {
        return new DeleteExecutor(
            $this->createJenkinsApi()
        );
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\Executor\ExecutorInterface
     */
    public function createEnableExecutor(): ExecutorInterface
    {
        return new EnableExecutor(
            $this->createJenkinsApi()
        );
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\Executor\ExecutorInterface
     */
    public function createDisableExecutor(): ExecutorInterface
    {
        return new DisableExecutor(
            $this->createJenkinsApi()
        );
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\Executor\ExecutorInterface
     */
    public function createNullExecutor(): ExecutorInterface
    {
        return new NullExecutor();
    }

    /**
     * @param string $idScheduler
     *
     * @return \Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface
     */
    public function createJenkinsApiConfigurationProvider(string $idScheduler): ConfigurationProviderInterface
    {
        return new ConfigurationProvider(
            $idScheduler,
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\Api\Builder\RequestBuilderInterface
     */
    public function createJenkinsApiRequestBuilder(): RequestBuilderInterface
    {
        return new RequestBuilder();
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\Api\Executor\RequestExecutorInterface
     */
    public function createJenkinsApiRequestExecutor(): RequestExecutorInterface
    {
        return new RequestExecutor(
            $this->getGuzzleClient()
        );
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\Processor\Builder\ConfigurationProviderBuilderInterface
     */
    public function createConfigurationProviderBuilder(): ConfigurationProviderBuilderInterface
    {
        return new ConfigurationProviderBuilder(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Dependency\Guzzle\SchedulerJenkinsToGuzzleInterface
     */
    public function getGuzzleClient(): SchedulerJenkinsToGuzzleInterface
    {
        return $this->getProvidedDependency(SchedulerJenkinsDependencyProvider::CLIENT_GUZZLE);
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Dependency\TwigEnvironment\SchedulerJenkinsToTwigEnvironmentInterface
     */
    public function getTwigEnvironment(): SchedulerJenkinsToTwigEnvironmentInterface
    {
        return $this->getProvidedDependency(SchedulerJenkinsDependencyProvider::TWIG_ENVIRONMENT);
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Dependency\Service\SchedulerJenkinsToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): SchedulerJenkinsToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(SchedulerJenkinsDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
