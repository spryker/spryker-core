<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SchedulerJenkins\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SchedulerRequestTransfer;
use Generated\Shared\Transfer\SchedulerResponseCollectionTransfer;
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
        $schedulerResponseTransfer = $this->createSchedulerResponseTransfer();
        $schedulerResponseTransfer = $this->getSchedulerFacade()->setupSchedulerJenkins('test', $scheduleTransfer, $schedulerResponseTransfer);
        $schedulerResponseMessages = $schedulerResponseTransfer->getMessages();

        $this->assertInstanceOf(SchedulerResponseCollectionTransfer::class, $schedulerResponseTransfer);
        $this->assertEquals(3, count($schedulerResponseMessages));
    }

    /**
     * @return void
     */
    public function testCleanSchedulerJenkins(): void
    {
        $scheduleTransfer = $this->createSchedulerTransfer();
        $schedulerResponseTransfer = $this->createSchedulerResponseTransfer();
        $schedulerResponseTransfer = $this->getSchedulerFacade()->cleanSchedulerJenkins('test', $scheduleTransfer, $schedulerResponseTransfer);
        $schedulerResponseMessages = $schedulerResponseTransfer->getMessages();

        $this->assertInstanceOf(SchedulerResponseCollectionTransfer::class, $schedulerResponseTransfer);
        $this->assertEquals(2, count($schedulerResponseMessages));
    }

    /**
     * @return void
     */
    public function testSuspendAllSchedulerJenkinsJobs(): void
    {
        $scheduleTransfer = $this->createSchedulerTransfer();
        $schedulerResponseTransfer = $this->createSchedulerResponseTransfer();
        $schedulerResponseTransfer = $this->getSchedulerFacade()->suspendSchedulerJenkins('test', $scheduleTransfer, $schedulerResponseTransfer);
        $schedulerResponseMessages = $schedulerResponseTransfer->getMessages();

        $this->assertInstanceOf(SchedulerResponseCollectionTransfer::class, $schedulerResponseTransfer);
        $this->assertEquals(2, count($schedulerResponseMessages));
    }

    /**
     * @return void
     */
    public function testSuspendByNameSchedulerJenkinsJobs(): void
    {
        $scheduleTransfer = $this->createSchedulerTransfer();
        $schedulerResponseTransfer = $this->createSchedulerResponseTransfer();
        $schedulerResponseTransfer = $this->getSchedulerFacade()->suspendSchedulerJenkins('test', $scheduleTransfer, $schedulerResponseTransfer);

        $this->assertInstanceOf(SchedulerResponseCollectionTransfer::class, $schedulerResponseTransfer);
    }

    /**
     * @return void
     */
    public function testResumeAllSchedulerJenkinsJobs(): void
    {
        $scheduleTransfer = $this->createSchedulerTransfer();
        $schedulerResponseTransfer = $this->createSchedulerResponseTransfer();
        $schedulerResponseTransfer = $this->getSchedulerFacade()->resumeSchedulerJenkins('test', $scheduleTransfer, $schedulerResponseTransfer);

        $this->assertInstanceOf(SchedulerResponseCollectionTransfer::class, $schedulerResponseTransfer);
    }

    /**
     * @return void
     */
    public function testResumeByNameSchedulerJenkinsJobs(): void
    {
        $scheduleTransfer = $this->createSchedulerTransfer();
        $schedulerResponseTransfer = $this->createSchedulerResponseTransfer();
        $schedulerResponseTransfer = $this->getSchedulerFacade()->resumeSchedulerJenkins('test', $scheduleTransfer, $schedulerResponseTransfer);

        $this->assertInstanceOf(SchedulerResponseCollectionTransfer::class, $schedulerResponseTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\SchedulerRequestTransfer
     */
    protected function createSchedulerTransfer(): SchedulerRequestTransfer
    {
        $testJobs = $this->getTestJobs();

        return (new SchedulerRequestTransfer())
            ->setJobs($testJobs)
            ->setSchedulers(['test']);
    }

    /**
     * @return \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer
     */
    protected function createSchedulerResponseTransfer(): SchedulerResponseCollectionTransfer
    {
        return new SchedulerResponseCollectionTransfer();
    }

    /**
     * @return \Spryker\Zed\SchedulerJenkins\Business\SchedulerJenkinsFacadeInterface
     */
    protected function getSchedulerFacade(): SchedulerJenkinsFacadeInterface
    {
        return (new SchedulerJenkinsFacade())
            ->setFactory($this->getSchedulerBusinessFactoryMock(new SchedulerResponseCollectionTransfer()));
    }

    /**
     * @param \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer $schedulerResponseTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SchedulerJenkins\Business\SchedulerJenkinsBusinessFactory
     */
    protected function getSchedulerBusinessFactoryMock(SchedulerResponseCollectionTransfer $schedulerResponseTransfer)
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
            ->willReturn($this->getJenkinsJobStatusUpdaterMock($schedulerResponseTransfer));

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
            ->willReturn('Job created');

        $schedulerJenkinsJobWriterMock
            ->method('updateJenkinsJob')
            ->willReturn('Job updated');

        $schedulerJenkinsJobWriterMock
            ->method('deleteJenkinsJob')
            ->willReturn('Job deleted');

        return $schedulerJenkinsJobWriterMock;
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
     * @param \Generated\Shared\Transfer\SchedulerResponseCollectionTransfer $schedulerResponseTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\SchedulerJenkins\Business\JobStatusUpdater\JenkinsJobStatusUpdaterInterface
     */
    protected function getJenkinsJobStatusUpdaterMock(SchedulerResponseCollectionTransfer $schedulerResponseTransfer)
    {
        $schedulerJenkinsJobStatusUpdaterMock = $this->getMockBuilder(JenkinsJobStatusUpdater::class)
            ->disableOriginalConstructor()
            ->getMock();

        $schedulerJenkinsJobStatusUpdaterMock
            ->method('updateAllJenkinsJobsStatus')
            ->willReturn($schedulerResponseTransfer->addMessage('Updated all'));

        $schedulerJenkinsJobStatusUpdaterMock
            ->method('updateJenkinsJobStatusByJobsName')
            ->willReturn($schedulerResponseTransfer->addMessage('Updated one'));

        return $schedulerJenkinsJobStatusUpdaterMock;
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
