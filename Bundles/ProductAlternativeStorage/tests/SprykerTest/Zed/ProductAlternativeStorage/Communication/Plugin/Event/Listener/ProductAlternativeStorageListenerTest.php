<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductAlternativeStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\ProductAlternative\Persistence\Map\SpyProductAlternativeTableMap;
use Spryker\Zed\ProductAlternative\Dependency\ProductAlternativeEvents;
use Spryker\Zed\ProductAlternativeStorage\Communication\Plugin\Event\Listener\ProductAlternativeStorageListener;
use Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageRepository;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductAlternativeStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductAlternativeStorageListenerTest
 * Add your own group annotations below this line
 */
class ProductAlternativeStorageListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductAlternativeStorage\ProductAlternativeStorageCommunicationTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageRepository
     */
    protected $productAlternativeStorageRepository;

    /**
     * @var \Spryker\Zed\ProductAlternativeStorage\Communication\Plugin\Event\Listener\ProductAlternativeStorageListener
     */
    protected $productAlternativeStorageListener;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $targetProductConcrete;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $alternativeProductConcrete;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->productAlternativeStorageRepository = new ProductAlternativeStorageRepository();

        $this->productAlternativeStorageListener = new ProductAlternativeStorageListener();
        $this->productAlternativeStorageListener->setFacade($this->tester->getMockedFacade());

        $this->targetProductConcrete = $this->tester->haveProduct();
        $this->alternativeProductConcrete = $this->tester->haveProduct();

        $this->tester->persistAlternativeForConcreteProduct($this->targetProductConcrete, [$this->alternativeProductConcrete->getSku()]);
    }

    /**
     * @return void
     */
    public function testHandleBulkProductAlternativeStorageEntityCanBePublished(): void
    {
        // Arrange
        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductAlternativeTableMap::COL_FK_PRODUCT => $this->targetProductConcrete->getIdProductConcrete(),
            ]),
        ];

        // Act
        $this->productAlternativeStorageListener->handleBulk(
            $eventTransfers,
            ProductAlternativeEvents::PRODUCT_ALTERNATIVE_PUBLISH
        );
        $productDiscontinuedEntityTransfers = $this->productAlternativeStorageRepository
            ->findProductAlternativeStorageEntities(
                [$this->targetProductConcrete->getIdProductConcrete()]
            );

        // Assert
        $this->assertCount(1, $productDiscontinuedEntityTransfers);
    }

    /**
     * @return void
     */
    public function testHandleBulkProductAlternativeStorageEntityCanBeUnPublished(): void
    {
        // Arrange
        $productAlternativeListTransfer = $this->tester->getProductAlternativeFacade()
            ->getProductAlternativeListByIdProductConcrete($this->targetProductConcrete->getIdProductConcrete());
        $productAlternativeTransfer = $productAlternativeListTransfer->getProductAlternatives()->offsetGet(0);
        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductAlternativeTableMap::COL_FK_PRODUCT => $this->targetProductConcrete->getIdProductConcrete(),
            ]),
        ];

        // Act
        $this->productAlternativeStorageListener->handleBulk(
            $eventTransfers,
            ProductAlternativeEvents::PRODUCT_ALTERNATIVE_PUBLISH
        );
        $this->tester->getProductAlternativeFacade()
            ->deleteProductAlternativeByIdProductAlternative($productAlternativeTransfer->getIdProductAlternative());
        $this->productAlternativeStorageListener->handleBulk(
            $eventTransfers,
            ProductAlternativeEvents::PRODUCT_ALTERNATIVE_UNPUBLISH
        );
        $productDiscontinuedEntityTransfers = $this->productAlternativeStorageRepository
            ->findProductAlternativeStorageEntities(
                [$this->targetProductConcrete->getIdProductConcrete()]
            );

        // Assert
        $this->assertCount(0, $productDiscontinuedEntityTransfers);
    }
}
