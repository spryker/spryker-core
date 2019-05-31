<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SchedulerJenkins\Business\Api\Builder\JenkinsResponseBuilder;
use Spryker\Zed\SchedulerJenkins\Business\Api\Builder\JenkinsResponseBuilderInterface;
use Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProvider;
use Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface;
use Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApi;
use Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApiInterface;
use Spryker\Zed\SchedulerJenkins\Business\Executor\CreateExecutor;
use Spryker\Zed\SchedulerJenkins\Business\Executor\DeleteExecutor;
use Spryker\Zed\SchedulerJenkins\Business\Executor\DisableExecutor;
use Spryker\Zed\SchedulerJenkins\Business\Executor\EnableExecutor;
use Spryker\Zed\SchedulerJenkins\Business\Executor\ExecutorInterface;
use Spryker\Zed\SchedulerJenkins\Business\Executor\NullExecutor;
use Spryker\Zed\SchedulerJenkins\Business\Executor\UpdateExecutor;
use Spryker\Zed\SchedulerJenkins\Business\Iterator\Builder\SchedulerResponseBuilder;
use Spryker\Zed\SchedulerJenkins\Business\Iterator\Builder\SchedulerResponseBuilderInterface;
use Spryker\Zed\SchedulerJenkins\Business\Iterator\Iterator;
use Spryker\Zed\SchedulerJenkins\Business\Iterator\IteratorInterface;
use Spryker\Zed\SchedulerJenkins\Business\Iterator\Strategy\ExecutionStrategyBuilder;
use Spryker\Zed\SchedulerJenkins\Business\Iterator\Strategy\ExecutionStrategyBuilderInterface;
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
            $this->getGuzzleClient(),
            $this->createJenkinsResponseBuilder(),
            $this->createJenkinsApiConfigurationReader()
        );
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\Iterator\IteratorInterface
     */
    public function createSchedulerJenkinsSetup(): IteratorInterface
    {
        return new Iterator(
            $this->createExecutionStrategyBuilder(
                $this->createUpdateExecutor(),
                $this->createCreateExecutor()
            ),
            $this->createSchedulerResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\Iterator\IteratorInterface
     */
    public function createSchedulerJenkinsClean(): IteratorInterface
    {
        return new Iterator(
            $this->createExecutionStrategyBuilder(
                $this->createDeleteExecutor(),
                $this->createNullExecutor()
            ),
            $this->createSchedulerResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\Iterator\IteratorInterface
     */
    public function createSchedulerJenkinsEnable(): IteratorInterface
    {
        return new Iterator(
            $this->createExecutionStrategyBuilder(
                $this->createEnableExecutor(),
                $this->createNullExecutor()
            ),
            $this->createSchedulerResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\Iterator\IteratorInterface
     */
    public function createSchedulerJenkinsDisable(): IteratorInterface
    {
        return new Iterator(
            $this->createExecutionStrategyBuilder(
                $this->createDisableExecutor(),
                $this->createNullExecutor()
            ),
            $this->createSchedulerResponseBuilder()
        );
    }

    /**
     * @param \Spryker\Zed\SchedulerJenkins\Business\Executor\ExecutorInterface $executorForExistingJob
     * @param \Spryker\Zed\SchedulerJenkins\Business\Executor\ExecutorInterface $executorForAbsentJob
     *
     * @return \Spryker\Zed\SchedulerJenkins\Business\Iterator\Strategy\ExecutionStrategyBuilderInterface
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
        return new NullExecutor(
            $this->createJenkinsResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\Iterator\Builder\SchedulerResponseBuilderInterface
     */
    public function createSchedulerResponseBuilder(): SchedulerResponseBuilderInterface
    {
        return new SchedulerResponseBuilder();
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\Api\Builder\JenkinsResponseBuilderInterface
     */
    public function createJenkinsResponseBuilder(): JenkinsResponseBuilderInterface
    {
        return new JenkinsResponseBuilder();
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\Api\Configuration\ConfigurationProviderInterface
     */
    public function createJenkinsApiConfigurationReader(): ConfigurationProviderInterface
    {
        return new ConfigurationProvider(
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
