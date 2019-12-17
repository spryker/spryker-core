<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Communication\Plugin\Event\Listener;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductBundleCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductForBundleTransfer;
use Spryker\Zed\ProductBundle\Business\ProductBundleFacade;
use Spryker\Zed\ProductBundle\Communication\Plugin\Event\Listener\ProductBundleBeforeUpdateListener;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundle
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductBundleBeforeUpdateListenerTest
 * Add your own group annotations below this line
 */
class ProductBundleBeforeUpdateListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductBundle\ProductBundleCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testProductConcreteDeactivatedIfAllBundledProductsAreDeactivated(): void
    {
        // Arrange
        $productBundleConcreteTransfer = $this->tester->haveProduct([
            ProductConcreteTransfer::IS_ACTIVE => true,
        ]);
        $bundledProductConcreteTransfer = $this->tester->haveProduct([
            ProductConcreteTransfer::IS_ACTIVE => false,
        ]);

        $productBundleConcreteTransfer = $this->tester->haveProductBundle(
            $productBundleConcreteTransfer,
            [],
            [
                [
                    ProductForBundleTransfer::ID_PRODUCT_BUNDLE => $productBundleConcreteTransfer->getIdProductConcrete(),
                    ProductForBundleTransfer::ID_PRODUCT_CONCRETE => $bundledProductConcreteTransfer->getIdProductConcrete(),
                ],
            ]
        );

        // Act
        $this->createProductBundleBeforeUpdateListener()->handle($productBundleConcreteTransfer, 'test.event');

        // Assert
        $this->assertFalse($productBundleConcreteTransfer->getIsActive());
    }

    /**
     * @return void
     */
    public function testAffectedBundleAvailabilityUpdated(): void
    {
        // Arrange
        $productBundleConcreteTransfer = $this->tester->haveProduct();
        $bundledProductConcreteTransfer1 = $this->tester->haveProduct();
        $bundledProductConcreteTransfer2 = $this->tester->haveProduct();

        $productBundleConcreteTransfer = $this->tester->haveProductBundle(
            $productBundleConcreteTransfer,
            [],
            [
                [
                    ProductForBundleTransfer::ID_PRODUCT_BUNDLE => $productBundleConcreteTransfer->getIdProductConcrete(),
                    ProductForBundleTransfer::ID_PRODUCT_CONCRETE => $bundledProductConcreteTransfer1->getIdProductConcrete(),
                ],
                [
                    ProductForBundleTransfer::ID_PRODUCT_BUNDLE => $productBundleConcreteTransfer->getIdProductConcrete(),
                    ProductForBundleTransfer::ID_PRODUCT_CONCRETE => $bundledProductConcreteTransfer2->getIdProductConcrete(),
                ],
            ]
        );

        $productBundleBeforeUpdateListener = $this->createProductBundleBeforeUpdateListener();
        $productBundleFacadeMock = $this->createProductBundleFacadeMock();
        $productBundleBeforeUpdateListener->setFacade($productBundleFacadeMock);
        $productBundleFacadeMock->method('findBundledProductsByIdProductConcrete')->willReturn(new ArrayObject());
        $productBundleFacadeMock->method('getProductBundleCollectionByCriteriaFilter')->willReturn(
            (new ProductBundleCollectionTransfer())->addProductBundle($productBundleConcreteTransfer->getProductBundle())
        );

        // Assert
        $productBundleFacadeMock
            ->expects($this->once())
            ->method('updateBundleAvailability');

        // Act
        $bundledProductConcreteTransfer1->setIsActive(!$bundledProductConcreteTransfer1->getIsActive());
        $productBundleBeforeUpdateListener->handle($productBundleConcreteTransfer, 'test.event');
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Communication\Plugin\Event\Listener\ProductBundleBeforeUpdateListener
     */
    protected function createProductBundleBeforeUpdateListener(): ProductBundleBeforeUpdateListener
    {
        return new ProductBundleBeforeUpdateListener();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Business\ProductBundleFacade
     */
    protected function createProductBundleFacadeMock()
    {
        return $this->getMockBuilder(ProductBundleFacade::class)->getMock();
    }
}
