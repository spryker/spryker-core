<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SchedulerJenkins\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SchedulerJobResponseTransfer;
use Generated\Shared\Transfer\SchedulerJobTransfer;
use Generated\Shared\Transfer\SchedulerResponseCollectionTransfer;
use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerScheduleTransfer;
use Spryker\Zed\SchedulerJenkins\Business\JobReader\JenkinsJobReader;
use Spryker\Zed\SchedulerJenkins\Business\JobStatusUpdater\JenkinsJobStatusUpdater;
use Spryker\Zed\SchedulerJenkins\Business\JobWriter\JenkinsJobWriter;
use Spryker\Zed\SchedulerJenkins\Business\SchedulerJenkinsBusinessFactory;
use Spryker\Zed\SchedulerJenkins\Business\SchedulerJenkinsFacade;
use Spryker\Zed\SchedulerJenkins\Business\SchedulerJenkinsFacadeInterface;
use Spryker\Zed\SchedulerJenkins\Business\TemplateGenerator\XmlJenkinsJobTemplateGenerator;

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
        $schedulerResponseTransfer = $this->getSchedulerFacade()->setupSchedulerJenkins($scheduleTransfer);
        $schedulerResponseMessages = $schedulerResponseTransfer->getSchedulerJobResponses();

        $this->assertInstanceOf(SchedulerResponseTransfer::class, $schedulerResponseTransfer);
        $this->assertNotEmpty($schedulerResponseMessages);
    }

    /**
     * @return void
     */
    public function testCleanSchedulerJenkins(): void
    {
        $scheduleTransfer = $this->createSchedulerTransfer();
        $schedulerResponseTransfer = $this->getSchedulerFacade()->cleanSchedulerJenkins($scheduleTransfer);
        $schedulerResponseMessages = $schedulerResponseTransfer->getSchedulerJobResponses();

        $this->assertInstanceOf(SchedulerResponseTransfer::class, $schedulerResponseTransfer);
        $this->assertNotEmpty($schedulerResponseMessages);
    }

    /**
     * @return void
     */
    public function testSuspendSchedulerJenkinsJobs(): void
    {
        $scheduleTransfer = $this->createSchedulerTransfer();
        $schedulerResponseTransfer = $this->getSchedulerFacade()->suspendSchedulerJenkins($scheduleTransfer);
        $schedulerResponseMessages = $schedulerResponseTransfer->getSchedulerJobResponses();

        $this->assertInstanceOf(SchedulerResponseTransfer::class, $schedulerResponseTransfer);
        $this->assertNotEmpty($schedulerResponseMessages);
    }

    /**
     * @return void
     */
    public function testResumeSchedulerJenkinsJobs(): void
    {
        $scheduleTransfer = $this->createSchedulerTransfer();
        $schedulerResponseTransfer = $this->getSchedulerFacade()->resumeSchedulerJenkins($scheduleTransfer);
        $schedulerResponseMessages = $schedulerResponseTransfer->getSchedulerJobResponses();

        $this->assertInstanceOf(SchedulerResponseTransfer::class, $schedulerResponseTransfer);
        $this->assertNotEmpty($schedulerResponseMessages);
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
            ->setIdScheduler('test')
            ->setStore('DE');
    }

    /**
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    protected function createSchedulerResponseCollectionTransfer(): SchedulerResponseCollectionTransfer
    {
        return new SchedulerResponseCollectionTransfer();
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
                    'createJenkinsJobReader',
                    'createJenkinsJobWriter',
                    'createXmkJenkinsJobTemplateGenerator',
                    'createJenkinsJobStatusUpdater',
                ]
            )
            ->getMock();

        $schedulerJenkinsBusinessFactoryMock
            ->method('createJenkinsJobReader')
            ->willReturn($this->getJenkinsJobReaderMock());

        $schedulerJenkinsBusinessFactoryMock
            ->method('createJenkinsJobWriter')
            ->willReturn($this->getJenkinsJobWriterMock());

        $schedulerJenkinsBusinessFactoryMock
            ->method('createXmkJenkinsJobTemplateGenerator')
            ->willReturn($this->getJenkinsJobXmlGeneratorMock());

        $schedulerJenkinsBusinessFactoryMock
            ->method('createJenkinsJobStatusUpdater')
            ->willReturn($this->getJenkinsJobStatusUpdaterMock());

        return $schedulerJenkinsBusinessFactoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SchedulerJenkins\Business\JobReader\JenkinsJobReaderInterface
     */
    protected function getJenkinsJobReaderMock()
    {
        $schedulerJenkinsJobReaderMock = $this->getMockBuilder(JenkinsJobReader::class)
            ->disableOriginalConstructor()
            ->getMock();

        $schedulerJenkinsJobReaderMock
            ->method('getExistingJobs')
            ->willReturn($this->getExistingJobs());

        return $schedulerJenkinsJobReaderMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SchedulerJenkins\Business\JobWriter\JenkinsJobWriterInterface
     */
    protected function getJenkinsJobWriterMock()
    {
        $schedulerJenkinsJobWriterMock = $this->getMockBuilder(JenkinsJobWriter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $schedulerJenkinsJobWriterMock
            ->method('createJenkinsJob')
            ->willReturn($this->createSchedulerJobResponseTransfer('createJenkinsJob'));

        $schedulerJenkinsJobWriterMock
            ->method('updateJenkinsJob')
            ->willReturn($this->createSchedulerJobResponseTransfer('updateJenkinsJob'));

        $schedulerJenkinsJobWriterMock
            ->method('deleteJenkinsJob')
            ->willReturn($this->createSchedulerJobResponseTransfer('deleteJenkinsJob'));

        return $schedulerJenkinsJobWriterMock;
    }

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\SchedulerJobResponseTransfer
     */
    protected function createSchedulerJobResponseTransfer(string $name): SchedulerJobResponseTransfer
    {
        return (new SchedulerJobResponseTransfer())->setName($name);
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
            ->method('getJobTemplate')
            ->willReturn('');

        return $schedulerJenkinsJobXmlTemplateGeneratorMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SchedulerJenkins\Business\JobStatusUpdater\JenkinsJobStatusUpdaterInterface
     */
    protected function getJenkinsJobStatusUpdaterMock()
    {
        $schedulerJenkinsJobStatusUpdaterMock = $this->getMockBuilder(JenkinsJobStatusUpdater::class)
            ->disableOriginalConstructor()
            ->getMock();

        $schedulerJenkinsJobStatusUpdaterMock
            ->method('updateJenkinsJobStatus')
            ->willReturn((new SchedulerResponseTransfer())
                ->addSchedulerJobResponse($this->createSchedulerJobResponseTransfer('updateJenkinsJobStatus')));

        return $schedulerJenkinsJobStatusUpdaterMock;
    }

    /**
     * @return string[]
     */
    protected function getExistingJobs(): array
    {
        return ['DE__test', 'DE__test1'];
    }
}
