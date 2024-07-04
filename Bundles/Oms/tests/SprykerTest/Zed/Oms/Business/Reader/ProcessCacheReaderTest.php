<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business\Reader;

use Codeception\Test\Unit;
use Spryker\Zed\Oms\Business\Process\ProcessInterface;
use Spryker\Zed\Oms\Business\Reader\ProcessCacheReader;
use Spryker\Zed\Oms\Business\Reader\ProcessCacheReaderInterface;
use Spryker\Zed\Oms\OmsConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Business
 * @group Reader
 * @group ProcessCacheReaderTest
 * Add your own group annotations below this line
 */
class ProcessCacheReaderTest extends Unit
{
    /**
     * @var \Spryker\Zed\Oms\OmsConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    protected OmsConfig $omsConfigMock;

    /**
     * @var \Spryker\Zed\Oms\Business\Reader\ProcessCacheReaderInterface
     */
    protected ProcessCacheReaderInterface $processCacheReader;

    /**
     * @var string
     */
    protected string $testDirectory;

    /**
     * @var string
     */
    protected const PROCESS_NAME = 'test_process';

    /**
     * @var string
     */
    protected const NON_EXISTING_PROCESS_NAME = 'non_existing_process';

    /**
     * @var string
     */
    protected const FILE_DATA = 'content';

    /**
     * @var string
     */
    protected const TEST_DIRECTORY_PATTERN = '/process_cache_reader_test/';

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->omsConfigMock = $this->createMock(OmsConfig::class);
        $this->testDirectory = sprintf('%s%s', sys_get_temp_dir(), static::TEST_DIRECTORY_PATTERN);
        mkdir($this->testDirectory);

        $this->omsConfigMock
            ->method('getProcessCachePath')
            ->willReturn($this->testDirectory);

        $this->processCacheReader = new ProcessCacheReader($this->omsConfigMock);
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
    public function testHasProcessReturnsTrueIfProcessExists(): void
    {
        file_put_contents(sprintf('%s%s', $this->testDirectory, static::PROCESS_NAME), static::FILE_DATA);

        $this->assertTrue($this->processCacheReader->hasProcess(static::PROCESS_NAME));
    }

    /**
     * @return void
     */
    public function testHasProcessReturnsFalseIfProcessDoesNotExist(): void
    {
        $this->assertFalse($this->processCacheReader->hasProcess(static::NON_EXISTING_PROCESS_NAME));
    }

    /**
     * @return void
     */
    public function testGetProcessReturnsProcessInterfaceInstance(): void
    {
        $processMock = $this->createMock(ProcessInterface::class);
        $serializedProcess = serialize($processMock);
        file_put_contents(sprintf('%s%s', $this->testDirectory, static::PROCESS_NAME), $serializedProcess);

        $process = $this->processCacheReader->getProcess(static::PROCESS_NAME);

        $this->assertInstanceOf(ProcessInterface::class, $process);
    }

    /**
     * @return void
     */
    public function testGetFullFilenameReturnsCorrectPath(): void
    {
        $expectedFullPath = sprintf('%s%s', $this->testDirectory, static::PROCESS_NAME);

        $this->assertSame($expectedFullPath, $this->processCacheReader->getFullFilename(static::PROCESS_NAME));
    }
}
