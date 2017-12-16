<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Collector\Business\Collector;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Collector\Business\Collector\AbstractDatabaseCollector;
use Spryker\Zed\Collector\Business\Exporter\Reader\Storage\RedisReader;
use Spryker\Zed\Collector\Business\Exporter\Writer\AbstractTouchUpdater;
use Spryker\Zed\Collector\Business\Exporter\Writer\Storage\RedisWriter;
use Spryker\Zed\Collector\Business\Model\BatchResult;
use SprykerTest\Zed\Collector\Business\ArrayBatchIterator;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\NullOutput;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Collector
 * @group Business
 * @group Collector
 * @group AbstractDatabaseCollectorTest
 * Add your own group annotations below this line
 */
class AbstractDatabaseCollectorTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Product\ProductBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\Collector\Business\Collector\AbstractDatabaseCollector
     */
    protected $sut;

    /**
     * @var \Symfony\Component\Console\Helper\ProgressBar
     */
    protected $progressBarHelper;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @var \Spryker\Zed\Collector\Business\Exporter\Reader\Storage\RedisReader|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $readerMock;

    /**
     * @var \Spryker\Zed\Collector\Business\Exporter\Writer\Storage\RedisWriter|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $writerMock;

    /**
     * @var \Spryker\Zed\Collector\Business\Exporter\Writer\AbstractTouchUpdater|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $touchUpdaterMock;

    /**
     * @uses AbstractDatabaseCollector::startProgressBar()
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->output = new NullOutput();
        $this->progressBarHelper = new ProgressBar($this->output);
        $this->localeTransfer =
            (new LocaleTransfer())
                ->setIdLocale(1)
                ->setLocaleName('DE');

        $this->readerMock = $this
            ->getMockBuilder(RedisReader::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->writerMock = $this
            ->getMockBuilder(RedisWriter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->touchUpdaterMock = $this
            ->getMockBuilder(AbstractTouchUpdater::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->sut = $this
            ->getMockBuilder(AbstractDatabaseCollector::class)
            ->setMethods(['startProgressBar'])
            ->getMockForAbstractClass();

        $this->sut
            ->expects($this->any())
            ->method('startProgressBar')
            ->willReturn($this->progressBarHelper);
    }

    /**
     * @return void
     */
    public function testA()
    {
        $this->sut->exportDataToStore(
            new ArrayBatchIterator([]),
            $this->touchUpdaterMock,
            new BatchResult(),
            $this->readerMock,
            $this->writerMock,
            $this->localeTransfer,
            $this->output
        );
    }
}
