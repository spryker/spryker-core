<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TaxProductStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\TaxProductStorageTransfer;
use Orm\Zed\TaxProductStorage\Persistence\SpyTaxProductStorage;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\TaxProductStorage\Communication\Plugin\Event\Listener\TaxProductStorageUnpublishListener;
use Spryker\Zed\TaxProductStorage\Persistence\TaxProductStorageRepository;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group TaxProductStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group TaxProductStorageUnpublishListenerTest
 * Add your own group annotations below this line
 */
class TaxProductStorageUnpublishListenerTest extends Unit
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
     * @var \Spryker\Zed\TaxProductStorage\Communication\Plugin\Event\Listener\TaxProductStorageUnpublishListener
     */
    protected $taxProductStorageUnpublishListener;

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
        $this->taxProductStorageUnpublishListener = new TaxProductStorageUnpublishListener();
        $this->productAbstractTransfer = $this->tester->haveProductAbstract();
    }

    /**
     * @return void
     */
    public function testHandleBulkTaxProductStorageEntityCanBeUnpublished(): void
    {
        // Arrange
        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productAbstractTransfer->getIdProductAbstract()),
        ];
        $this->saveTaxProductStorageEntity();

        // Act
        $this->taxProductStorageUnpublishListener->handleBulk(
            $eventTransfers,
            ProductEvents::PRODUCT_ABSTRACT_UNPUBLISH
        );
        $taxProductStorageEntities = $this->taxProductStorageRepository
            ->getSynchronizationDataTransfersFromTaxProductStoragesByProductAbstractIds([
                $this->productAbstractTransfer->getIdProductAbstract(),
            ]);

        // Assert
        $this->assertCount(0, $taxProductStorageEntities);
    }

    /**
     * @return void
     */
    protected function saveTaxProductStorageEntity(): void
    {
        $taxProductStorageTransfer = (new TaxProductStorageTransfer())
            ->setSku($this->productAbstractTransfer->getSku())
            ->setIdTaxSet($this->productAbstractTransfer->getIdTaxSet())
            ->setIdProductAbstract($this->productAbstractTransfer->getIdProductAbstract());

        $taxProductStorageEntity = (new SpyTaxProductStorage())
            ->setData($taxProductStorageTransfer->toArray())
            ->setSku($this->productAbstractTransfer->getSku())
            ->setFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract());

        $taxProductStorageEntity->save();
    }
}
