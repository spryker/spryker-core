<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Scheduler\Communication\Plugin\ConfigurationReader;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SchedulerScheduleTransfer;
use Spryker\Zed\Scheduler\Business\SchedulerBusinessFactory;
use Spryker\Zed\Scheduler\Business\SchedulerFacade;
use Spryker\Zed\Scheduler\Business\SchedulerFacadeInterface;
use Spryker\Zed\Scheduler\Communication\Plugin\Scheduler\PhpScheduleReaderPlugin;
use Spryker\Zed\Scheduler\SchedulerConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Scheduler
 * @group Communication
 * @group Plugin
 * @group ConfigurationReader
 * @group PhpSchedulerReaderPluginTest
 * Add your own group annotations below this line
 */
class PhpSchedulerReaderPluginTest extends Unit
{
    protected const PHP_FILE_CONFIGURATION = 'test-php-configuration.php';

    /**
     * @var \SprykerTest\Zed\Scheduler\SchedulerBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testReadSchedulerFromPhpFileSource(): void
    {
        $phpSchedulerReader = (new PhpScheduleReaderPlugin())
            ->setFacade($this->getSchedulerFacade());

        $scheduleTransfer = $phpSchedulerReader->readSchedule(new SchedulerScheduleTransfer());

        $this->assertNotEmpty($scheduleTransfer->getJobs());

        $this->assertCount(2, $scheduleTransfer->getJobs());
    }

    /**
     * @return \Spryker\Zed\Scheduler\Business\SchedulerFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getSchedulerFacade(): SchedulerFacadeInterface
    {
        return (new SchedulerFacade())
            ->setFactory(
                (new SchedulerBusinessFactory())
                    ->setConfig($this->getSchedulerConfigMock())
            );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Graph\GraphConfig
     */
    protected function getSchedulerConfigMock()
    {
        $configMock = $this->getMockBuilder(SchedulerConfig::class)
            ->getMock();

        $configMock
            ->method('getPhpSchedulerReaderPath')
            ->willReturn($this->getTestCronJobsDefinitionPhpFilePath());

        return $configMock;
    }

    /**
     * @return string
     */
    protected function getTestCronJobsDefinitionPhpFilePath(): string
    {
        return codecept_data_dir() . static::PHP_FILE_CONFIGURATION;
    }
}
