<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */


namespace Unit\Spryker\Zed\Collector\Business\Exporter\Writer\File\Adapter;

use Spryker\Zed\Collector\Business\Exporter\Exception\FileWriterException;
use Spryker\Zed\Collector\Business\Exporter\Writer\File\Adapter\AdapterInterface;
use Unit\Spryker\Zed\Collector\Business\Exporter\Writer\File\Adapter\Mock\WriterAdapter;

/**
 * @group Spryker
 * @group Zed
 * @group Collector
 * @group Business
 * @group CsvWriterAdapter
 */
class AbstractAdapterTest extends \PHPUnit_Framework_TestCase
{

    const FILE_NAME = 'fileName';

    /**
     * @return void
     */
    public function testGetAbsolutePathShouldThrowExceptionIfDirectoryNotSet()
    {
        $writerAdapter = new WriterAdapter(null);
        $this->setExpectedException(FileWriterException::class);

        $writerAdapter->getAbsolutePath();
    }

    /**
     * @return void
     */
    public function testGetAbsolutePathShouldThrowExceptionIfFileNotSet()
    {
        $writerAdapter = new WriterAdapter(__DIR__);
        $this->setExpectedException(FileWriterException::class);

        $writerAdapter->getAbsolutePath();
    }

    /**
     * @return void
     */
    public function testSetDirectoryShouldReturnInstance()
    {
        $writerAdapter = new WriterAdapter(null);

        $this->assertInstanceOf(AdapterInterface::class, $writerAdapter->setDirectory(__DIR__));
    }

    /**
     * @return void
     */
    public function testSetFileNameShouldReturnInstance()
    {
        $writerAdapter = new WriterAdapter(__DIR__);

        $this->assertInstanceOf(AdapterInterface::class, $writerAdapter->setFileName(self::FILE_NAME));
    }

    /**
     * @return void
     */
    public function testGetAbsolutePathShouldReturnPath()
    {
        $writerAdapter = new WriterAdapter(__DIR__);
        $writerAdapter->setFileName(self::FILE_NAME);

        $this->assertSame(__DIR__ . DIRECTORY_SEPARATOR . self::FILE_NAME, $writerAdapter->getAbsolutePath());
    }

}
