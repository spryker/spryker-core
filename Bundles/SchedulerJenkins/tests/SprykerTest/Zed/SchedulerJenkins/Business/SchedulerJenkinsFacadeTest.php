<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SchedulerJenkins\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer;
use Generated\Shared\Transfer\SchedulerJobTransfer;
use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerScheduleTransfer;
use Spryker\Service\UtilEncoding\UtilEncodingService;
use Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApi;
use Spryker\Zed\SchedulerJenkins\Business\Processor\Builder\ConfigurationProviderBuilderInterface;
use Spryker\Zed\SchedulerJenkins\Business\Processor\Configuration\ConfigurationProvider;
use Spryker\Zed\SchedulerJenkins\Business\SchedulerJenkinsBusinessFactory;
use Spryker\Zed\SchedulerJenkins\Business\SchedulerJenkinsFacade;
use Spryker\Zed\SchedulerJenkins\Business\SchedulerJenkinsFacadeInterface;
use Spryker\Zed\SchedulerJenkins\Dependency\Service\SchedulerJenkinsToUtilEncodingServiceBridge;
use Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group SchedulerJenkins
 * @group Business
 * @group Facade
 * @group SchedulerJenkinsFacadeTest
 * Add your own group annotations below this line
 */
class SchedulerJenkinsFacadeTest extends Unit
{
    protected const ID_SCHEDULER = 'test';

    /**
     * @return void
     */
    public function testSetupSchedulerJenkins(): void
    {
        $scheduleTransfer = $this->createSchedulerTransfer();
        $responseTransfer = $this->getSchedulerFacade()->setupJenkins($scheduleTransfer);

        $this->assertInstanceOf(SchedulerResponseTransfer::class, $responseTransfer);
        $this->assertTrue($responseTransfer->getStatus());
        $this->assertEmpty($responseTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testCleanSchedulerJenkins(): void
    {
        $scheduleTransfer = $this->createSchedulerTransfer();
        $responseTransfer = $this->getSchedulerFacade()->cleanJenkins($scheduleTransfer);

        $this->assertInstanceOf(SchedulerResponseTransfer::class, $responseTransfer);
        $this->assertTrue($responseTransfer->getStatus());
        $this->assertEmpty($responseTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testSuspendSchedulerJenkinsJobs(): void
    {
        $scheduleTransfer = $this->createSchedulerTransfer();
        $responseTransfer = $this->getSchedulerFacade()->suspendJenkins($scheduleTransfer);

        $this->assertInstanceOf(SchedulerResponseTransfer::class, $responseTransfer);
        $this->assertTrue($responseTransfer->getStatus());
        $this->assertEmpty($responseTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testResumeSchedulerJenkinsJobs(): void
    {
        $scheduleTransfer = $this->createSchedulerTransfer();
        $responseTransfer = $this->getSchedulerFacade()->resumeJenkins($scheduleTransfer);

        $this->assertInstanceOf(SchedulerResponseTransfer::class, $responseTransfer);
        $this->assertTrue($responseTransfer->getStatus());
        $this->assertEmpty($responseTransfer->getMessage());
    }

    /**
     * @return \Generated\Shared\Transfer\SchedulerScheduleTransfer
     */
    protected function createSchedulerTransfer(): SchedulerScheduleTransfer
    {
        return (new SchedulerScheduleTransfer())
            ->addJob(
                (new SchedulerJobTransfer())
                    ->setName('DE__test')
                    ->setStore('DE')
                    ->setEnable(true)
                    ->setRepeatPattern('* * * * *')
                    ->setPayload([])
            )
            ->addJob(
                (new SchedulerJobTransfer())
                    ->setName('DE__test1')
                    ->setStore('DE')
                    ->setEnable(true)
                    ->setRepeatPattern('* * * * *')
                    ->setPayload([])
            )
            ->setIdScheduler(static::ID_SCHEDULER);
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\SchedulerJenkinsFacadeInterface
     */
    protected function getSchedulerFacade(): SchedulerJenkinsFacadeInterface
    {
        return (new SchedulerJenkinsFacade())
            ->setFactory($this->getSchedulerBusinessFactoryMock());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SchedulerJenkins\Business\SchedulerJenkinsBusinessFactory
     */
    protected function getSchedulerBusinessFactoryMock()
    {
        $schedulerJenkinsBusinessFactoryMock = $this->getMockBuilder(SchedulerJenkinsBusinessFactory::class)
            ->setMethods(
                [
                    'createXmkJenkinsJobTemplateGenerator',
                    'createJenkinsApi',
                    'getUtilEncodingService',
                    'createConfigurationProviderBuilder',
                ]
            )
            ->getMock();

        $schedulerJenkinsBusinessFactoryMock
            ->method('createJenkinsApi')
            ->willReturn($this->createJenkinsApiMock());

        $schedulerJenkinsBusinessFactoryMock
            ->method('createConfigurationProviderBuilder')
            ->willReturn($this->createConfigurationProviderBuilder());

        $schedulerJenkinsBusinessFactoryMock
            ->method('getUtilEncodingService')
            ->willReturn($this->createUtilEncodingServiceBridge());

        return $schedulerJenkinsBusinessFactoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SchedulerJenkins\Business\Processor\Builder\ConfigurationProviderBuilderInterface
     */
    protected function createConfigurationProviderBuilder()
    {
        $configurationProviderBuilderMock = $this->getMockBuilder(ConfigurationProviderBuilderInterface::class)
            ->setMethods([
                'build',
            ])
            ->getMock();

        $configurationProviderBuilderMock
            ->method('build')
            ->willReturn(new ConfigurationProvider(static::ID_SCHEDULER, $this->createSchedulerJenkinsConfigMock()));

        return $configurationProviderBuilderMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApiInterface
     */
    protected function createJenkinsApiMock()
    {
        $jenkinsApiMock = $this->getMockBuilder(JenkinsApi::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getJobs',
                'createJob',
                'updateJob',
                'deleteJob',
                'enableJob',
                'disableJob',
            ])
            ->getMock();

        $jenkinsApiMock
            ->method('getJobs')
            ->willReturn((new SchedulerJenkinsResponseTransfer())
                    ->setPayload('{"jobs" : [{"name":"DE__test"},{"name":"DE__test1"}]}')
                    ->setStatus(true));

        $jenkinsApiMock
            ->method('createJob')
            ->willReturn($this->createSchedulerJenkinsResponseTransfer());

        $jenkinsApiMock
            ->method('updateJob')
            ->willReturn($this->createSchedulerJenkinsResponseTransfer());

        $jenkinsApiMock
            ->method('deleteJob')
            ->willReturn($this->createSchedulerJenkinsResponseTransfer());

        $jenkinsApiMock
            ->method('enableJob')
            ->willReturn($this->createSchedulerJenkinsResponseTransfer());

        $jenkinsApiMock
            ->method('disableJob')
            ->willReturn($this->createSchedulerJenkinsResponseTransfer());

        return $jenkinsApiMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig
     */
    protected function createSchedulerJenkinsConfigMock()
    {
        $schedulerJenkinsConfigMock = $this->getMockBuilder(SchedulerJenkinsConfig::class)
            ->setMethods(['getJenkinsConfiguration'])
            ->getMock();

        $schedulerJenkinsConfigMock
            ->method('getJenkinsConfiguration')
            ->willReturn('test');

        return $schedulerJenkinsConfigMock;
    }

    /**
     * @return \Generated\Shared\Transfer\SchedulerJenkinsResponseTransfer
     */
    protected function createSchedulerJenkinsResponseTransfer(): SchedulerJenkinsResponseTransfer
    {
        return (new SchedulerJenkinsResponseTransfer())->setStatus(true);
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Dependency\Service\SchedulerJenkinsToUtilEncodingServiceBridge
     */
    protected function createUtilEncodingServiceBridge(): SchedulerJenkinsToUtilEncodingServiceBridge
    {
        return new SchedulerJenkinsToUtilEncodingServiceBridge(
            new UtilEncodingService()
        );
    }
}
