<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApi;
use Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApiInterface;
use Spryker\Zed\SchedulerJenkins\Business\Executor\CreateExecutor;
use Spryker\Zed\SchedulerJenkins\Business\Executor\DeleteExecutor;
use Spryker\Zed\SchedulerJenkins\Business\Executor\DisableExecutor;
use Spryker\Zed\SchedulerJenkins\Business\Executor\EnableExecutor;
use Spryker\Zed\SchedulerJenkins\Business\Executor\ExecutorInterface;
use Spryker\Zed\SchedulerJenkins\Business\Executor\NullExecutor;
use Spryker\Zed\SchedulerJenkins\Business\Executor\UpdateExecutor;
use Spryker\Zed\SchedulerJenkins\Business\Iterator\Iterator;
use Spryker\Zed\SchedulerJenkins\Business\Iterator\IteratorInterface;
use Spryker\Zed\SchedulerJenkins\Business\Reader\JenkinsJobReader;
use Spryker\Zed\SchedulerJenkins\Business\Reader\JenkinsJobReaderInterface;
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
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\Iterator\IteratorInterface
     */
    public function createSchedulerJenkinsSetup(): IteratorInterface
    {
        return new Iterator(
            $this->createJenkinsJobReader(),
            $this->createUpdateExecutor(),
            $this->createCreateExecutor()
        );
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\Iterator\IteratorInterface
     */
    public function createSchedulerJenkinsClean(): IteratorInterface
    {
        return new Iterator(
            $this->createJenkinsJobReader(),
            $this->createDeleteExecutor(),
            $this->createNullExecutor()
        );
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\Iterator\IteratorInterface
     */
    public function createSchedulerJenkinsEnable(): IteratorInterface
    {
        return new Iterator(
            $this->createJenkinsJobReader(),
            $this->createEnableExecutor(),
            $this->createNullExecutor()
        );
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\Iterator\IteratorInterface
     */
    public function createSchedulerJenkinsDisable(): IteratorInterface
    {
        return new Iterator(
            $this->createJenkinsJobReader(),
            $this->createDisableExecutor(),
            $this->createNullExecutor()
        );
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\Reader\JenkinsJobReaderInterface
     */
    public function createJenkinsJobReader(): JenkinsJobReaderInterface
    {
        return new JenkinsJobReader(
            $this->createJenkinsApi(),
            $this->getUtilEncodingService(),
            $this->getConfig()
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
