<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SchedulerJenkins\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApi;
use Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApiInterface;
use Spryker\Zed\SchedulerJenkins\Business\Clean\JenkinsJobClean;
use Spryker\Zed\SchedulerJenkins\Business\Clean\JenkinsJobCleanInterface;
use Spryker\Zed\SchedulerJenkins\Business\JobReader\JenkinsJobReader;
use Spryker\Zed\SchedulerJenkins\Business\JobReader\JenkinsJobReaderInterface;
use Spryker\Zed\SchedulerJenkins\Business\JobStatusUpdater\JenkinsJobStatusUpdater;
use Spryker\Zed\SchedulerJenkins\Business\JobStatusUpdater\JenkinsJobStatusUpdaterInterface;
use Spryker\Zed\SchedulerJenkins\Business\JobWriter\JenkinsJobWriter;
use Spryker\Zed\SchedulerJenkins\Business\JobWriter\JenkinsJobWriterInterface;
use Spryker\Zed\SchedulerJenkins\Business\Resume\JenkinsResume;
use Spryker\Zed\SchedulerJenkins\Business\Resume\JenkinsResumeInterface;
use Spryker\Zed\SchedulerJenkins\Business\Setup\SchedulerJenkinsSetup;
use Spryker\Zed\SchedulerJenkins\Business\Setup\SchedulerJenkinsSetupInterface;
use Spryker\Zed\SchedulerJenkins\Business\Suspend\JenkinsSuspend;
use Spryker\Zed\SchedulerJenkins\Business\Suspend\JenkinsSuspendInterface;
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
     * @return \Spryker\Zed\SchedulerJenkins\Business\Setup\SchedulerJenkinsSetupInterface
     */
    public function createSchedulerJenkinsSetup(): SchedulerJenkinsSetupInterface
    {
        return new SchedulerJenkinsSetup(
            $this->createJenkinsJobReader(),
            $this->createJenkinsJobWriter(),
            $this->createXmkJenkinsJobTemplateGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\JobReader\JenkinsJobReaderInterface
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
     * @return \Spryker\Zed\SchedulerJenkins\Business\JobWriter\JenkinsJobWriterInterface
     */
    public function createJenkinsJobWriter(): JenkinsJobWriterInterface
    {
        return new JenkinsJobWriter(
            $this->createJenkinsApi(),
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
     * @return \Spryker\Zed\SchedulerJenkins\Business\Resume\JenkinsResumeInterface
     */
    public function createJenkinsResume(): JenkinsResumeInterface
    {
        return new JenkinsResume(
            $this->createJenkinsJobStatusUpdater()
        );
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\Suspend\JenkinsSuspendInterface
     */
    public function createJenkinsSuspend(): JenkinsSuspendInterface
    {
        return new JenkinsSuspend(
            $this->createJenkinsJobStatusUpdater()
        );
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\Clean\JenkinsJobCleanInterface
     */
    public function createJenkinsJobClean(): JenkinsJobCleanInterface
    {
        return new JenkinsJobClean(
            $this->createJenkinsJobReader(),
            $this->createJenkinsJobWriter()
        );
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\JobStatusUpdater\JenkinsJobStatusUpdaterInterface
     */
    public function createJenkinsJobStatusUpdater(): JenkinsJobStatusUpdaterInterface
    {
        return new JenkinsJobStatusUpdater(
            $this->createJenkinsApi(),
            $this->getConfig(),
            $this->createJenkinsJobReader()
        );
    }

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
     * @return \Spryker\Zed\SchedulerJenkins\Dependency\Guzzle\SchedulerJenkinsToGuzzleInterface
     */
    public function getGuzzleClient(): SchedulerJenkinsToGuzzleInterface
    {
        return $this->getProvidedDependency(SchedulerJenkinsDependencyProvider::GUZZLE_CLIENT);
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
