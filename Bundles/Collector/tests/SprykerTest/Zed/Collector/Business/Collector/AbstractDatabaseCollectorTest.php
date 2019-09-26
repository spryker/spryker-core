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
use Spryker\Zed\Collector\CollectorConfig;
use Spryker\Zed\Touch\Persistence\TouchQueryContainer;
use SprykerTest\Zed\Collector\Business\ArrayBatchIterator;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\NullOutput;

/**
 * Auto-generated group annotations
 *
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
     * @var \Spryker\Zed\Collector\Business\Collector\AbstractDatabaseCollector|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $abstractDatabaseCollectorMock;

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
     * @var \Spryker\Zed\Collector\Business\Exporter\Reader\Storage\RedisReader|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $readerMock;

    /**
     * @var \Spryker\Zed\Collector\Business\Exporter\Writer\Storage\RedisWriter|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $writerMock;

    /**
     * @var \Spryker\Zed\Collector\Business\Exporter\Writer\AbstractTouchUpdater|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $touchUpdaterMock;

    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $touchQueryContainerMock;

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
        $this->touchQueryContainerMock = $this
            ->getMockBuilder(TouchQueryContainer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->abstractDatabaseCollectorMock = $this
            ->getMockBuilder(AbstractDatabaseCollector::class)
            ->setMethods(['startProgressBar', 'isStorable', 'collectKey', 'processCollectedItem'])
            ->getMockForAbstractClass();

        $this->abstractDatabaseCollectorMock->setTouchQueryContainer($this->touchQueryContainerMock);

        $this->abstractDatabaseCollectorMock
            ->expects($this->any())
            ->method('startProgressBar')
            ->willReturn($this->progressBarHelper);
    }

    /**
     * @return void
     */
    public function testExportDataToStoreWritesStorableData()
    {
        // Assign
        $this->abstractDatabaseCollectorMock
            ->expects($this->any())
            ->method('isStorable')
            ->willReturn(true);

        $this->abstractDatabaseCollectorMock
            ->expects($this->any())
            ->method('collectKey')
            ->willReturn('touchKey1');

        $this->abstractDatabaseCollectorMock
            ->expects($this->any())
            ->method('processCollectedItem')
            ->willReturn([]);

        $batchCollection = [
            [
                0 => [
                    CollectorConfig::COLLECTOR_RESOURCE_ID => 1,
                ],
            ],
        ];

        // Assert
        $this->touchUpdaterMock
            ->expects($this->exactly(1))
            ->method('bulkUpdate');
        $this->writerMock
            ->expects($this->exactly(1))
            ->method('write');

        $this->touchUpdaterMock
            ->expects($this->exactly(0))
            ->method('deleteTouchKeyEntities');
        $this->writerMock
            ->expects($this->exactly(0))
            ->method('delete');

        // Act
        $this->abstractDatabaseCollectorMock->exportDataToStore(
            new ArrayBatchIterator($batchCollection),
            $this->touchUpdaterMock,
            new BatchResult(),
            $this->readerMock,
            $this->writerMock,
            $this->localeTransfer,
            $this->output
        );
    }

    /**
     * @return void
     */
    public function testExportDataToStoreDeletesNotStorableData()
    {
        // Assign
        $this->abstractDatabaseCollectorMock
            ->expects($this->any())
            ->method('isStorable')
            ->willReturn(false);

        $this->abstractDatabaseCollectorMock
            ->expects($this->any())
            ->method('collectKey')
            ->willReturn('touchKey1');

        $this->abstractDatabaseCollectorMock
            ->expects($this->any())
            ->method('processCollectedItem')
            ->willReturn([]);

        $batchCollection = [
            [
                0 => [
                    CollectorConfig::COLLECTOR_RESOURCE_ID => 1,
                ],
            ],
        ];

        // Assert
        $this->touchUpdaterMock
            ->expects($this->exactly(0))
            ->method('bulkUpdate');
        $this->writerMock
            ->expects($this->exactly(0))
            ->method('write');

        $this->touchUpdaterMock
            ->expects($this->exactly(1))
            ->method('deleteTouchKeyEntities');
        $this->writerMock
            ->expects($this->exactly(1))
            ->method('delete');

        // Act
        $this->abstractDatabaseCollectorMock->exportDataToStore(
            new ArrayBatchIterator($batchCollection),
            $this->touchUpdaterMock,
            new BatchResult(),
            $this->readerMock,
            $this->writerMock,
            $this->localeTransfer,
            $this->output
        );
    }
}
