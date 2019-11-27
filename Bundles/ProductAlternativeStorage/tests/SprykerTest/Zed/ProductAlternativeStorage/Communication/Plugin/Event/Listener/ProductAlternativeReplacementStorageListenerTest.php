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
use Spryker\Zed\ProductAlternativeStorage\Communication\Plugin\Event\Listener\ProductAlternativeReplacementStorageListener;
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
 * @group ProductAlternativeReplacementStorageListenerTest
 * Add your own group annotations below this line
 */
class ProductAlternativeReplacementStorageListenerTest extends Unit
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
     * @var \Spryker\Zed\ProductAlternativeStorage\Communication\Plugin\Event\Listener\ProductAlternativeReplacementStorageListener
     */
    protected $productAlternativeReplacementStorageListener;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $targetProductConcrete;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $alternativeProductConcrete;

    /**
     * @var \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected $alternativeProductAbstract;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->productAlternativeStorageRepository = new ProductAlternativeStorageRepository();

        $this->productAlternativeReplacementStorageListener = new ProductAlternativeReplacementStorageListener();
        $this->productAlternativeReplacementStorageListener->setFacade($this->tester->getMockedFacade());

        $this->targetProductConcrete = $this->tester->haveProduct();
        $this->alternativeProductConcrete = $this->tester->haveProduct();
        $this->alternativeProductAbstract = $this->tester->haveProductAbstract();

        $this->tester->persistAlternativeForConcreteProduct($this->targetProductConcrete, [
            $this->alternativeProductConcrete->getSku(),
            $this->alternativeProductAbstract->getSku(),
        ]);
    }

    /**
     * @return void
     */
    public function testHandleBulkProductReplacementForStorageEntityCanBePublishedForConcreteAlternative(): void
    {
        // Arrange
        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductAlternativeTableMap::COL_FK_PRODUCT_CONCRETE_ALTERNATIVE => $this->alternativeProductConcrete->getIdProductConcrete(),
            ]),
        ];

        // Act
        $this->productAlternativeReplacementStorageListener->handleBulk(
            $eventTransfers,
            ProductAlternativeEvents::PRODUCT_ALTERNATIVE_PUBLISH
        );
        $productReplacementStorageEntity = $this->productAlternativeStorageRepository
            ->findProductReplacementStorageEntitiesBySku(
                $this->alternativeProductConcrete->getSku()
            );

        // Assert
        $this->assertNotNull($productReplacementStorageEntity);
    }

    /**
     * @return void
     */
    public function testHandleBulkProductReplacementForStorageEntityCanBeUnpublishedForConcreteAlternative(): void
    {
        // Arrange
        $productAlternativeListTransfer = $this->tester->getProductAlternativeFacade()
            ->getProductAlternativeListByIdProductConcrete($this->targetProductConcrete->getIdProductConcrete());
        $productAlternativeTransfer = $productAlternativeListTransfer->getProductAlternatives()->offsetGet(0);
        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductAlternativeTableMap::COL_FK_PRODUCT_CONCRETE_ALTERNATIVE => $this->alternativeProductConcrete->getIdProductConcrete(),
            ]),
        ];

        // Act
        $this->productAlternativeReplacementStorageListener->handleBulk(
            $eventTransfers,
            ProductAlternativeEvents::PRODUCT_ALTERNATIVE_PUBLISH
        );
        $this->tester->getProductAlternativeFacade()
            ->deleteProductAlternativeByIdProductAlternative($productAlternativeTransfer->getIdProductAlternative());
        $this->productAlternativeReplacementStorageListener->handleBulk(
            $eventTransfers,
            ProductAlternativeEvents::PRODUCT_ALTERNATIVE_UNPUBLISH
        );
        $productReplacementStorageEntity = $this->productAlternativeStorageRepository
            ->findProductReplacementStorageEntitiesBySku(
                $this->alternativeProductConcrete->getSku()
            );

        // Assert
        $this->assertNull($productReplacementStorageEntity);
    }

    /**
     * @return void
     */
    public function testHandleBulkProductReplacementForStorageEntityCanBePublishedForAbstractAlternative(): void
    {
        // Arrange
        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductAlternativeTableMap::COL_FK_PRODUCT_ABSTRACT_ALTERNATIVE => $this->alternativeProductAbstract->getIdProductAbstract(),
            ]),
        ];

        // Act
        $this->productAlternativeReplacementStorageListener->handleBulk(
            $eventTransfers,
            ProductAlternativeEvents::PRODUCT_ALTERNATIVE_PUBLISH
        );
        $productReplacementStorageEntity = $this->productAlternativeStorageRepository
            ->findProductReplacementStorageEntitiesBySku(
                $this->alternativeProductAbstract->getSku()
            );

        // Assert
        $this->assertNotNull($productReplacementStorageEntity);
    }

    /**
     * @return void
     */
    public function testHandleBulkProductReplacementForStorageEntityCanBeUnpublishedForAbstractAlternative(): void
    {
        // Arrange
        $productAlternativeListTransfer = $this->tester->getProductAlternativeFacade()
            ->getProductAlternativeListByIdProductConcrete($this->targetProductConcrete->getIdProductConcrete());
        $productAlternativeTransfer = $productAlternativeListTransfer->getProductAlternatives()->offsetGet(1);
        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductAlternativeTableMap::COL_FK_PRODUCT_ABSTRACT_ALTERNATIVE => $this->alternativeProductAbstract->getIdProductAbstract(),
            ]),
        ];

        // Act
        $this->productAlternativeReplacementStorageListener->handleBulk(
            $eventTransfers,
            ProductAlternativeEvents::PRODUCT_ALTERNATIVE_PUBLISH
        );
        $this->tester->getProductAlternativeFacade()
            ->deleteProductAlternativeByIdProductAlternative($productAlternativeTransfer->getIdProductAlternative());
        $this->productAlternativeReplacementStorageListener->handleBulk(
            $eventTransfers,
            ProductAlternativeEvents::PRODUCT_ALTERNATIVE_UNPUBLISH
        );
        $productReplacementStorageEntity = $this->productAlternativeStorageRepository
            ->findProductReplacementStorageEntitiesBySku(
                $this->alternativeProductAbstract->getSku()
            );

        // Assert
        $this->assertNull($productReplacementStorageEntity);
    }
}
