<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\JenkinsScheduler\Business;

use Spryker\Zed\JenkinsScheduler\Business\Api\JenkinsApi;
use Spryker\Zed\JenkinsScheduler\Business\Api\JenkinsApiInterface;
use Spryker\Zed\JenkinsScheduler\Business\Clean\JenkinsJobClean;
use Spryker\Zed\JenkinsScheduler\Business\Clean\JenkinsJobCleanInterface;
use Spryker\Zed\JenkinsScheduler\Business\JobReader\JenkinsJobReader;
use Spryker\Zed\JenkinsScheduler\Business\JobReader\JenkinsJobReaderInterface;
use Spryker\Zed\JenkinsScheduler\Business\JobStatusUpdater\JenkinsJobStatusUpdater;
use Spryker\Zed\JenkinsScheduler\Business\JobStatusUpdater\JenkinsJobStatusUpdaterInterface;
use Spryker\Zed\JenkinsScheduler\Business\JobWriter\JenkinsJobWriter;
use Spryker\Zed\JenkinsScheduler\Business\JobWriter\JenkinsJobWriterInterface;
use Spryker\Zed\JenkinsScheduler\Business\Resume\JenkinsResume;
use Spryker\Zed\JenkinsScheduler\Business\Resume\JenkinsResumeInterface;
use Spryker\Zed\JenkinsScheduler\Business\Setup\JenkinsSchedulerSetup;
use Spryker\Zed\JenkinsScheduler\Business\Setup\JenkinsSchedulerSetupInterface;
use Spryker\Zed\JenkinsScheduler\Business\Suspend\JenkinsSuspend;
use Spryker\Zed\JenkinsScheduler\Business\Suspend\JenkinsSuspendInterface;
use Spryker\Zed\JenkinsScheduler\Business\TemplateGenerator\JenkinsJobTemplateGeneratorInterface;
use Spryker\Zed\JenkinsScheduler\Business\TemplateGenerator\XmlJenkinsJobTemplateGenerator;
use Spryker\Zed\JenkinsScheduler\Dependency\Guzzle\JenkinsSchedulerToGuzzleInterface;
use Spryker\Zed\JenkinsScheduler\Dependency\Service\JenkinsSchedulerToUtilEncodingServiceInterface;
use Spryker\Zed\JenkinsScheduler\Dependency\TwigEnvironment\JenkinsSchedulerToTwigEnvironmentInterface;
use Spryker\Zed\JenkinsScheduler\JenkinsSchedulerDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\JenkinsScheduler\JenkinsSchedulerConfig getConfig()
 */
class JenkinsSchedulerBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\JenkinsScheduler\Business\Setup\JenkinsSchedulerSetupInterface
     */
    public function createJenkinsSchedulerSetup(): JenkinsSchedulerSetupInterface
    {
        return new JenkinsSchedulerSetup(
            $this->createJenkinsJobReader(),
            $this->createJenkinsJobWriter(),
            $this->createXmkJenkinsJobTemplateGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\JenkinsScheduler\Business\JobReader\JenkinsJobReaderInterface
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
     * @return \Spryker\Zed\JenkinsScheduler\Business\JobWriter\JenkinsJobWriterInterface
     */
    public function createJenkinsJobWriter(): JenkinsJobWriterInterface
    {
        return new JenkinsJobWriter(
            $this->createJenkinsApi(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\JenkinsScheduler\Business\TemplateGenerator\JenkinsJobTemplateGeneratorInterface
     */
    public function createXmkJenkinsJobTemplateGenerator(): JenkinsJobTemplateGeneratorInterface
    {
        return new XmlJenkinsJobTemplateGenerator(
            $this->getTwigEnvironment(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\JenkinsScheduler\Business\Resume\JenkinsResumeInterface
     */
    public function createJenkinsResume(): JenkinsResumeInterface
    {
        return new JenkinsResume(
            $this->createJenkinsJobStatusUpdater()
        );
    }

    /**
     * @return \Spryker\Zed\JenkinsScheduler\Business\Suspend\JenkinsSuspendInterface
     */
    public function createJenkinsSuspend(): JenkinsSuspendInterface
    {
        return new JenkinsSuspend(
            $this->createJenkinsJobStatusUpdater()
        );
    }

    /**
     * @return \Spryker\Zed\JenkinsScheduler\Business\Clean\JenkinsJobCleanInterface
     */
    public function createJenkinsJobClean(): JenkinsJobCleanInterface
    {
        return new JenkinsJobClean(
            $this->createJenkinsJobReader(),
            $this->createJenkinsJobWriter()
        );
    }

    /**
     * @return \Spryker\Zed\JenkinsScheduler\Business\JobStatusUpdater\JenkinsJobStatusUpdaterInterface
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
     * @return \Spryker\Zed\JenkinsScheduler\Business\Api\JenkinsApiInterface
     */
    public function createJenkinsApi(): JenkinsApiInterface
    {
        return new JenkinsApi(
            $this->getGuzzleClient(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\JenkinsScheduler\Dependency\Guzzle\JenkinsSchedulerToGuzzleInterface
     */
    public function getGuzzleClient(): JenkinsSchedulerToGuzzleInterface
    {
        return $this->getProvidedDependency(JenkinsSchedulerDependencyProvider::GUZZLE_CLIENT);
    }

    /**
     * @return \Spryker\Zed\JenkinsScheduler\Dependency\TwigEnvironment\JenkinsSchedulerToTwigEnvironmentInterface
     */
    public function getTwigEnvironment(): JenkinsSchedulerToTwigEnvironmentInterface
    {
        return $this->getProvidedDependency(JenkinsSchedulerDependencyProvider::TWIG_ENVIRONMENT);
    }

    /**
     * @return \Spryker\Zed\JenkinsScheduler\Dependency\Service\JenkinsSchedulerToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): JenkinsSchedulerToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(JenkinsSchedulerDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
