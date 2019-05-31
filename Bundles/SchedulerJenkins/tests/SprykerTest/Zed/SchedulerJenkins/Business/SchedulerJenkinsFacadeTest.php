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
use Spryker\Zed\SchedulerJenkins\Business\SchedulerJenkinsBusinessFactory;
use Spryker\Zed\SchedulerJenkins\Business\SchedulerJenkinsFacade;
use Spryker\Zed\SchedulerJenkins\Business\SchedulerJenkinsFacadeInterface;
use Spryker\Zed\SchedulerJenkins\Business\TemplateGenerator\XmlJenkinsJobTemplateGenerator;
use Spryker\Zed\SchedulerJenkins\Dependency\Service\SchedulerJenkinsToUtilEncodingServiceBridge;

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
    /**
     * @return void
     */
    public function testSetupSchedulerJenkins(): void
    {
        $scheduleTransfer = $this->createSchedulerTransfer();
        $responseTransfer = $this->getSchedulerFacade()->setupJenkins($scheduleTransfer);

        $this->assertInstanceOf(SchedulerResponseTransfer::class, $responseTransfer);
        $this->assertTrue($responseTransfer->getStatus());
        $this->assertNull($responseTransfer->getMessage());
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
        $this->assertNull($responseTransfer->getMessage());
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
        $this->assertNull($responseTransfer->getMessage());
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
        $this->assertNull($responseTransfer->getMessage());
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
                    ->setSchedule('* * * * *')
                    ->setPayload([])
            )
            ->addJob(
                (new SchedulerJobTransfer())
                    ->setName('DE__test1')
                    ->setStore('DE')
                    ->setEnable(true)
                    ->setSchedule('* * * * *')
                    ->setPayload([])
            )
            ->setIdScheduler('test');
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
                ]
            )
            ->getMock();

        $schedulerJenkinsBusinessFactoryMock
            ->method('createXmkJenkinsJobTemplateGenerator')
            ->willReturn($this->getJenkinsJobXmlGeneratorMock());

        $schedulerJenkinsBusinessFactoryMock
            ->method('createJenkinsApi')
            ->willReturn($this->createJenkinsApiMock());

        $schedulerJenkinsBusinessFactoryMock
            ->method('getUtilEncodingService')
            ->willReturn($this->createUtilEncodingServiceBridge());

        return $schedulerJenkinsBusinessFactoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SchedulerJenkins\Business\Api\JenkinsApiInterface
     */
    protected function createJenkinsApiMock()
    {
        $jenkinsApiMock = $this->getMockBuilder(JenkinsApi::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'executeGetRequest',
                'executePostRequest',
            ])
            ->getMock();

        $jenkinsApiMock
            ->method('executeGetRequest')
            ->willReturn((new SchedulerJenkinsResponseTransfer())
                    ->setPayload('{"jobs" : [{"name":"DE__test"},{"name":"DE__test1"}]}')
                    ->setStatus(true));

        $jenkinsApiMock
            ->method('executePostRequest')
            ->willReturn((new SchedulerJenkinsResponseTransfer())->setStatus(true));

        return $jenkinsApiMock;
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

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SchedulerJenkins\Business\TemplateGenerator\JenkinsJobTemplateGeneratorInterface
     */
    protected function getJenkinsJobXmlGeneratorMock()
    {
        $schedulerJenkinsJobXmlTemplateGeneratorMock = $this->getMockBuilder(XmlJenkinsJobTemplateGenerator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $schedulerJenkinsJobXmlTemplateGeneratorMock
            ->method('generateJobTemplate')
            ->willReturn('');

        return $schedulerJenkinsJobXmlTemplateGeneratorMock;
    }
}
