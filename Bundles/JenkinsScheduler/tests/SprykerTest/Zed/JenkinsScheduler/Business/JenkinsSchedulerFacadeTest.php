<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\JenkinsScheduler\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SchedulerResponseTransfer;
use Generated\Shared\Transfer\SchedulerTransfer;
use Spryker\Zed\JenkinsScheduler\Business\JenkinsSchedulerBusinessFactory;
use Spryker\Zed\JenkinsScheduler\Business\JenkinsSchedulerFacade;
use Spryker\Zed\JenkinsScheduler\Business\JenkinsSchedulerFacadeInterface;
use Spryker\Zed\JenkinsScheduler\Business\JobReader\JenkinsJobReader;
use Spryker\Zed\JenkinsScheduler\Business\JobStatusUpdater\JenkinsJobStatusUpdater;
use Spryker\Zed\JenkinsScheduler\Business\JobWriter\JenkinsJobWriter;
use Spryker\Zed\JenkinsScheduler\Business\TemplateGenerator\XmlJenkinsJobTemplateGenerator;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group JenkinsScheduler
 * @group Business
 * @group Facade
 * @group JenkinsSchedulerFacadeTest
 * Add your own group annotations below this line
 */
class JenkinsSchedulerFacadeTest extends Unit
{
    /**
     * @return void
     */
    public function testSetupJenkinsScheduler(): void
    {
        $schedulerTransfer = $this->createSchedulerTransfer();
        $schedulerResponseTransfer = $this->createSchedulerResponseTransfer();
        $schedulerResponseTransfer = $this->getSchedulerFacade()->setupJenkinsScheduler('test', $schedulerTransfer, $schedulerResponseTransfer);
        $schedulerResponseMessages = $schedulerResponseTransfer->getMessages();

        $this->assertInstanceOf(SchedulerResponseTransfer::class, $schedulerResponseTransfer);
        $this->assertEquals(3, count($schedulerResponseMessages));
    }

    /**
     * @return void
     */
    public function testCleanJenkinsScheduler(): void
    {
        $schedulerTransfer = $this->createSchedulerTransfer();
        $schedulerResponseTransfer = $this->createSchedulerResponseTransfer();
        $schedulerResponseTransfer = $this->getSchedulerFacade()->cleanJenkinsScheduler('test', $schedulerTransfer, $schedulerResponseTransfer);
        $schedulerResponseMessages = $schedulerResponseTransfer->getMessages();

        $this->assertInstanceOf(SchedulerResponseTransfer::class, $schedulerResponseTransfer);
        $this->assertEquals(2, count($schedulerResponseMessages));
    }

    /**
     * @return void
     */
    public function testSuspendAllJenkinsSchedulerJobs(): void
    {
        $schedulerTransfer = $this->createSchedulerTransfer();
        $schedulerResponseTransfer = $this->createSchedulerResponseTransfer();
        $schedulerResponseTransfer = $this->getSchedulerFacade()->suspendJenkinsScheduler('test', $schedulerTransfer, $schedulerResponseTransfer);
        $schedulerResponseMessages = $schedulerResponseTransfer->getMessages();

        $this->assertInstanceOf(SchedulerResponseTransfer::class, $schedulerResponseTransfer);
        $this->assertEquals(2, count($schedulerResponseMessages));
    }

    /**
     * @return void
     */
    public function testSuspendByNameJenkinsSchedulerJobs(): void
    {
        $schedulerTransfer = $this->createSchedulerTransfer();
        $schedulerResponseTransfer = $this->createSchedulerResponseTransfer();
        $schedulerResponseTransfer = $this->getSchedulerFacade()->suspendJenkinsScheduler('test', $schedulerTransfer, $schedulerResponseTransfer);

        $this->assertInstanceOf(SchedulerResponseTransfer::class, $schedulerResponseTransfer);
    }

    /**
     * @return void
     */
    public function testResumeAllJenkinsSchedulerJobs(): void
    {
        $schedulerTransfer = $this->createSchedulerTransfer();
        $schedulerResponseTransfer = $this->createSchedulerResponseTransfer();
        $schedulerResponseTransfer = $this->getSchedulerFacade()->resumeJenkinsScheduler('test', $schedulerTransfer, $schedulerResponseTransfer);

        $this->assertInstanceOf(SchedulerResponseTransfer::class, $schedulerResponseTransfer);
    }

    /**
     * @return void
     */
    public function testResumeByNameJenkinsSchedulerJobs(): void
    {
        $schedulerTransfer = $this->createSchedulerTransfer();
        $schedulerResponseTransfer = $this->createSchedulerResponseTransfer();
        $schedulerResponseTransfer = $this->getSchedulerFacade()->resumeJenkinsScheduler('test', $schedulerTransfer, $schedulerResponseTransfer);

        $this->assertInstanceOf(SchedulerResponseTransfer::class, $schedulerResponseTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\SchedulerTransfer
     */
    protected function createSchedulerTransfer(): SchedulerTransfer
    {
        $testJobs = $this->getTestJobs();

        return (new SchedulerTransfer())
            ->setJobs($testJobs)
            ->setSchedulers(['test']);
    }

    /**
     * @return \Generated\Shared\Transfer\SchedulerResponseTransfer
     */
    protected function createSchedulerResponseTransfer(): SchedulerResponseTransfer
    {
        return new SchedulerResponseTransfer();
    }

    /**
     * @return \Spryker\Zed\JenkinsScheduler\Business\JenkinsSchedulerFacadeInterface
     */
    protected function getSchedulerFacade(): JenkinsSchedulerFacadeInterface
    {
        return (new JenkinsSchedulerFacade())
            ->setFactory($this->getSchedulerBusinessFactoryMock(new SchedulerResponseTransfer()));
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerResponseTransfer $schedulerResponseTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\JenkinsScheduler\Business\JenkinsSchedulerBusinessFactory
     */
    protected function getSchedulerBusinessFactoryMock(SchedulerResponseTransfer $schedulerResponseTransfer)
    {
        $jenkinsSchedulerBusinessFactoryMock = $this->getMockBuilder(JenkinsSchedulerBusinessFactory::class)
            ->setMethods(
                [
                    'createJenkinsJobReader',
                    'createJenkinsJobWriter',
                    'createXmkJenkinsJobTemplateGenerator',
                    'createJenkinsJobStatusUpdater',
                ]
            )
            ->getMock();

        $jenkinsSchedulerBusinessFactoryMock
            ->method('createJenkinsJobReader')
            ->willReturn($this->getJenkinsJobReaderMock());

        $jenkinsSchedulerBusinessFactoryMock
            ->method('createJenkinsJobWriter')
            ->willReturn($this->getJenkinsJobWriterMock());

        $jenkinsSchedulerBusinessFactoryMock
            ->method('createXmkJenkinsJobTemplateGenerator')
            ->willReturn($this->getJenkinsJobXmlGeneratorMock());

        $jenkinsSchedulerBusinessFactoryMock
            ->method('createJenkinsJobStatusUpdater')
            ->willReturn($this->getJenkinsJobStatusUpdaterMock($schedulerResponseTransfer));

        return $jenkinsSchedulerBusinessFactoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\JenkinsScheduler\Business\JobReader\JenkinsJobReaderInterface
     */
    protected function getJenkinsJobReaderMock()
    {
        $jenkinsSchedulerJobReaderMock = $this->getMockBuilder(JenkinsJobReader::class)
            ->disableOriginalConstructor()
            ->getMock();

        $jenkinsSchedulerJobReaderMock
            ->method('getExistingJobs')
            ->willReturn($this->getExistingJobs());

        return $jenkinsSchedulerJobReaderMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\JenkinsScheduler\Business\JobWriter\JenkinsJobWriterInterface
     */
    protected function getJenkinsJobWriterMock()
    {
        $jenkinsSchedulerJobWriterMock = $this->getMockBuilder(JenkinsJobWriter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $jenkinsSchedulerJobWriterMock
            ->method('createJenkinsJob')
            ->willReturn('Job created');

        $jenkinsSchedulerJobWriterMock
            ->method('updateJenkinsJob')
            ->willReturn('Job updated');

        $jenkinsSchedulerJobWriterMock
            ->method('deleteJenkinsJob')
            ->willReturn('Job deleted');

        return $jenkinsSchedulerJobWriterMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\JenkinsScheduler\Business\TemplateGenerator\JenkinsJobTemplateGeneratorInterface
     */
    protected function getJenkinsJobXmlGeneratorMock()
    {
        $jenkinsSchedulerJobXmlTemplateGeneratorMock = $this->getMockBuilder(XmlJenkinsJobTemplateGenerator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $jenkinsSchedulerJobXmlTemplateGeneratorMock
            ->method('getJobTemplate')
            ->willReturn('');

        return $jenkinsSchedulerJobXmlTemplateGeneratorMock;
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerResponseTransfer $schedulerResponseTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\JenkinsScheduler\Business\JobStatusUpdater\JenkinsJobStatusUpdaterInterface
     */
    protected function getJenkinsJobStatusUpdaterMock(SchedulerResponseTransfer $schedulerResponseTransfer)
    {
        $jenkinsSchedulerJobStatusUpdaterMock = $this->getMockBuilder(JenkinsJobStatusUpdater::class)
            ->disableOriginalConstructor()
            ->getMock();

        $jenkinsSchedulerJobStatusUpdaterMock
            ->method('updateAllJenkinsJobsStatus')
            ->willReturn($schedulerResponseTransfer->addMessage('Updated all'));

        $jenkinsSchedulerJobStatusUpdaterMock
            ->method('updateJenkinsJobStatusByJobsName')
            ->willReturn($schedulerResponseTransfer->addMessage('Updated one'));

        return $jenkinsSchedulerJobStatusUpdaterMock;
    }

    /**
     * @return array
     */
    protected function getTestJobs(): array
    {
        return [
            "test" => [
                "name" => "test",
                "command" => "test",
                "schedule" => "* * * * *",
                "enable" => true,
                "run_on_non_production" => true,
                "request" => "///",
                "store" => "DE",
            ],
            "test1" => [
                "name" => "test1",
                "command" => "test1",
                "schedule" => "* * * * *",
                "enable" => true,
                "run_on_non_production" => true,
                "request" => "///",
                "store" => "DE",
            ],
            "test2" => [
                "name" => "test2",
                "command" => "test2",
                "schedule" => "* * * * *",
                "enable" => true,
                "run_on_non_production" => true,
                "request" => "///",
                "store" => "DE",
            ],
        ];
    }

    /**
     * @return string[]
     */
    protected function getExistingJobs(): array
    {
        return ['test', 'test2'];
    }
}
