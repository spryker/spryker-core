<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */


namespace Unit\Spryker\Zed\Collector\Business\Exporter\Writer\File;

use Spryker\Zed\Collector\Business\Exporter\Writer\File\Adapter\AdapterInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\File\FileWriter;
use Spryker\Zed\Collector\Business\Exporter\Writer\File\FileWriterInterface;

/**
 * @group Spryker
 * @group Zed
 * @group Collector
 * @group Business
 * @group FileWriter
 */
class FileWriterTest extends \PHPUnit_Framework_TestCase
{

    const BYTES_WRITTEN_TO_FILE = 12;

    /**
     * @return void
     */
    public function testInstantiation()
    {
        $writerAdapter = $this->getWriterAdapter();
        $fileWriter = new FileWriter($writerAdapter);

        $this->assertInstanceOf(FileWriterInterface::class, $fileWriter);
    }

    /**
     * @return void
     */
    public function testSetFileNameShouldSetFileNameInAdapter()
    {
        $writerAdapter = $this->getWriterAdapter();
        $writerAdapter->expects($this->once())->method('setFileName');
        $fileWriter = new FileWriter($writerAdapter);
        $fileWriter->setFileName('foo');
    }

    /**
     * @return void
     */
    public function testWriteShouldReturnTrueWhenDataWrittenToFile()
    {
        $writerAdapter = $this->getWriterAdapter();
        $writerAdapter->expects($this->once())->method('write')->willReturn(self::BYTES_WRITTEN_TO_FILE);
        $fileWriter = new FileWriter($writerAdapter);

        $this->assertTrue($fileWriter->write(['data' => 'for file']));
    }

    /**
     * @return void
     */
    public function testWriteShouldReturnFalseWhenDataNotWrittenToFile()
    {
        $writerAdapter = $this->getWriterAdapter();
        $writerAdapter->expects($this->once())->method('write')->willReturn(0);
        $fileWriter = new FileWriter($writerAdapter);

        $this->assertFalse($fileWriter->write(['data' => 'for file']));
    }

    /**
     * @return void
     */
    public function testDeleteShouldAlwaysReturnFalse()
    {
        $writerAdapter = $this->getWriterAdapter();
        $fileWriter = new FileWriter($writerAdapter);

        $this->assertFalse($fileWriter->delete([]));
    }

    /**
     * @return void
     */
    public function testGetNameShouldReturnString()
    {
        $writerAdapter = $this->getWriterAdapter();
        $fileWriter = new FileWriter($writerAdapter);

        $this->assertInternalType('string', $fileWriter->getName());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Collector\Business\Exporter\Writer\File\Adapter\AdapterInterface
     */
    protected function getWriterAdapter()
    {
        return $this->getMock(AdapterInterface::class);
    }

}
