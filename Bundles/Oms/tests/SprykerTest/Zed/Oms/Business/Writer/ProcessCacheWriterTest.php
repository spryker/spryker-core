<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business\Writer;

use Codeception\Test\Unit;
use Spryker\Zed\Oms\Business\Process\ProcessInterface;
use Spryker\Zed\Oms\Business\Reader\ProcessCacheReaderInterface;
use Spryker\Zed\Oms\Business\Writer\ProcessCacheWriter;
use Spryker\Zed\Oms\Business\Writer\ProcessCacheWriterInterface;
use Spryker\Zed\Oms\OmsConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Business
 * @group Writer
 * @group ProcessCacheWriterTest
 *  Add your own group annotations below this line
 */
class ProcessCacheWriterTest extends Unit
{
    /**
     * @var \Spryker\Zed\Oms\OmsConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    protected OmsConfig $omsConfigMock;

    /**
     * @var \Spryker\Zed\Oms\Business\Reader\ProcessCacheReaderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected ProcessCacheReaderInterface $processCacheReaderMock;

    /**
     * @var \Spryker\Zed\Oms\Business\Writer\ProcessCacheWriterInterface
     */
    protected ProcessCacheWriterInterface $processCacheWriter;

    /**
     * @var string
     */
    protected string $testDirectory;

    /**
     * @var string
     */
    protected const TEST_DIRECTORY_PATTERN = '/process_cache_writer_tests/';

    /**
     * @var string
     */
    protected const PROCESS_NAME = 'test_process';

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->omsConfigMock = $this->createMock(OmsConfig::class);
        $this->processCacheReaderMock = $this->createMock(ProcessCacheReaderInterface::class);
        $this->testDirectory = sprintf('%s%s', sys_get_temp_dir(), static::TEST_DIRECTORY_PATTERN);
        mkdir($this->testDirectory);

        $this->omsConfigMock
            ->method('getProcessCachePath')
            ->willReturn($this->testDirectory);

        $this->processCacheWriter = new ProcessCacheWriter($this->omsConfigMock, $this->processCacheReaderMock);
    }

    /**
     * @return void
     */
    protected function _after(): void
    {
        parent::_after();

        array_map('unlink', glob(sprintf('%s%s', $this->testDirectory, '*')));
        rmdir($this->testDirectory);
    }

    /**
     * @return void
     */
    public function testCacheProcessCreatesCacheFileWithGivenProcessName(): void
    {
        $processMock = $this->createMock(ProcessInterface::class);
        $processMock->method('getName')->willReturn(static::PROCESS_NAME);

        $this->processCacheReaderMock
            ->method('getFullFilename')
            ->with(static::PROCESS_NAME)
            ->willReturn(sprintf('%s%s', $this->testDirectory, static::PROCESS_NAME));

        $cachedFilePath = $this->processCacheWriter->cacheProcess($processMock, static::PROCESS_NAME);

        $this->assertFileExists($cachedFilePath);
        $this->assertSame(sprintf('%s%s', $this->testDirectory, static::PROCESS_NAME), $cachedFilePath);
    }

    /**
     * @return void
     */
    public function testCacheProcessCreatesCacheFileWithDefaultProcessName(): void
    {
        $processMock = $this->createMock(ProcessInterface::class);
        $processMock->method('getName')->willReturn(static::PROCESS_NAME);

        $this->processCacheReaderMock
            ->method('getFullFilename')
            ->with(static::PROCESS_NAME)
            ->willReturn(sprintf('%s%s', $this->testDirectory, static::PROCESS_NAME));

        $cachedFilePath = $this->processCacheWriter->cacheProcess($processMock);

        $this->assertFileExists($cachedFilePath);
        $this->assertSame(sprintf('%s%s', $this->testDirectory, static::PROCESS_NAME), $cachedFilePath);
    }
}
