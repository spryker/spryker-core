<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TaxProductStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\TaxProductStorage\Communication\Plugin\Event\Listener\TaxProductStoragePublishListener;
use Spryker\Zed\TaxProductStorage\Persistence\TaxProductStorageRepository;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group TaxProductStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group TaxProductStoragePublishListenerTest
 * Add your own group annotations below this line
 */
class TaxProductStoragePublishListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\TaxProductStorage\TaxProductStorageCommunicationTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\TaxProductStorage\Persistence\TaxProductStorageRepositoryInterface
     */
    protected $taxProductStorageRepository;

    /**
     * @var \Spryker\Zed\TaxProductStorage\Communication\Plugin\Event\Listener\TaxProductStoragePublishListener
     */
    protected $taxProductStoragePublishListener;

    /**
     * @var \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected $productAbstractTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });

        $this->taxProductStorageRepository = new TaxProductStorageRepository();
        $this->taxProductStoragePublishListener = new TaxProductStoragePublishListener();
        $this->productAbstractTransfer = $this->tester->haveProductAbstract();
    }

    /**
     * @return void
     */
    public function testHandleBulkTaxProductStorageEntityCanBePublished(): void
    {
        // Arrange
        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productAbstractTransfer->getIdProductAbstract()),
        ];

        // Act
        $this->taxProductStoragePublishListener->handleBulk(
            $eventTransfers,
            ProductEvents::PRODUCT_ABSTRACT_PUBLISH
        );
        $synchronizationDataTransfers = $this->taxProductStorageRepository
            ->getSynchronizationDataTransfersFromTaxProductStoragesByProductAbstractIds(
                [$this->productAbstractTransfer->getIdProductAbstract()]
            );

        // Assert
        $this->assertCount(1, $synchronizationDataTransfers);
        /** @var array $synchronizationDataTransfersDataArray */
        $synchronizationDataTransfersDataArray = $synchronizationDataTransfers[0]->getData();
        $this->assertEquals($this->productAbstractTransfer->getSku(), $synchronizationDataTransfersDataArray['sku']);
    }
}
