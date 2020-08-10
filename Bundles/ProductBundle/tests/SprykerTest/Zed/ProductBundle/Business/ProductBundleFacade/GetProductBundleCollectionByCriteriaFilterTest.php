<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business\ProductBundleFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductBundleCriteriaFilterTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundle
 * @group Business
 * @group ProductBundleFacade
 * @group GetProductBundleCollectionByCriteriaFilterTest
 * Add your own group annotations below this line
 */
class GetProductBundleCollectionByCriteriaFilterTest extends Unit
{
    protected const FAKE_ID_PRODUCT_CONCRETE = 6666;

    /**
     * @var \SprykerTest\Zed\ProductBundle\ProductBundleBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetProductBundleCollectionByCriteriaFilterWithProductConcreteIdFilter(): void
    {
        // Arrange
        $productConcreteBundleTransfer = $this->tester->haveProductBundle($this->tester->haveFullProduct());

        $productBundleCriteriaFilterTransfer = (new ProductBundleCriteriaFilterTransfer())
            ->addIdProductConcrete($productConcreteBundleTransfer->getIdProductConcrete());

        // Act
        $productBundleTransfers = $this->tester->getFacade()
            ->getProductBundleCollectionByCriteriaFilter($productBundleCriteriaFilterTransfer)
            ->getProductBundles();

        // Assert
        $this->assertCount(3, $productBundleTransfers);
        $this->assertEquals(
            $productBundleTransfers->offsetGet(0),
            $productBundleTransfers->offsetGet(1)
        );
    }

    /**
     * @return void
     */
    public function testGetProductBundleCollectionByCriteriaFilterWithFakeProductConcreteIdFilter(): void
    {
        // Arrange
        $productBundleCriteriaFilterTransfer = (new ProductBundleCriteriaFilterTransfer())
            ->addIdProductConcrete(static::FAKE_ID_PRODUCT_CONCRETE);

        // Act
        $productBundleTransfers = $this->tester->getFacade()
            ->getProductBundleCollectionByCriteriaFilter($productBundleCriteriaFilterTransfer)
            ->getProductBundles();

        // Assert
        $this->assertEmpty($productBundleTransfers);
    }

    /**
     * @return void
     */
    public function testGetProductBundleCollectionByCriteriaFilterWithLimit(): void
    {
        // Arrange
        $productBundleCriteriaFilterTransfer = (new ProductBundleCriteriaFilterTransfer())
            ->setFilter((new FilterTransfer())->setLimit(1));

        // Act
        $productBundleTransfers = $this->tester->getFacade()
            ->getProductBundleCollectionByCriteriaFilter($productBundleCriteriaFilterTransfer)
            ->getProductBundles();

        // Assert
        $this->assertCount(1, $productBundleTransfers);
    }

    /**
     * @return void
     */
    public function testGetProductBundleCollectionByCriteriaFilterWithGroupedFilter(): void
    {
        // Arrange
        $productConcreteBundleTransfer = $this->tester->haveProductBundle($this->tester->haveFullProduct());

        $productBundleCriteriaFilterTransfer = (new ProductBundleCriteriaFilterTransfer())
            ->addIdProductConcrete($productConcreteBundleTransfer->getIdProductConcrete())
            ->setApplyGrouped(true);

        // Act
        $productBundleTransfers = $this->tester->getFacade()
            ->getProductBundleCollectionByCriteriaFilter($productBundleCriteriaFilterTransfer)
            ->getProductBundles();

        // Assert
        $this->assertCount(1, $productBundleTransfers);
        $this->assertCount(3, $productBundleTransfers->offsetGet(0)->getBundledProducts());
    }

    /**
     * @return void
     */
    public function testGetProductBundleCollectionByCriteriaFilterWithBundledProductIdFilter(): void
    {
        // Arrange
        $productConcreteBundleTransfer = $this->tester->haveProductBundle($this->tester->haveFullProduct());
        $idBundledProduct = $productConcreteBundleTransfer->getProductBundle()
            ->getBundledProducts()
            ->getIterator()
            ->current()
            ->getIdProductConcrete();

        $productBundleCriteriaFilterTransfer = (new ProductBundleCriteriaFilterTransfer())
            ->addIdBundledProduct($idBundledProduct)
            ->setApplyGrouped(true);

        // Act
        $productBundleTransfers = $this->tester->getFacade()
            ->getProductBundleCollectionByCriteriaFilter($productBundleCriteriaFilterTransfer)
            ->getProductBundles();

        // Assert
        $this->assertCount(1, $productBundleTransfers);
        $this->assertCount(
            1,
            $productBundleTransfers->getIterator()->current()->getBundledProducts()
        );
    }

    /**
     * @return void
     */
    public function testGetProductBundleCollectionByCriteriaFilterWithDifferentBundledProductIdsFilter(): void
    {
        // Arrange
        $firstProductConcreteBundleTransfer = $this->tester->haveProductBundle($this->tester->haveFullProduct());
        $secondProductConcreteBundleTransfer = $this->tester->haveProductBundle($this->tester->haveFullProduct());

        $productBundleCriteriaFilterTransfer = (new ProductBundleCriteriaFilterTransfer())
            ->addIdBundledProduct(
                $firstProductConcreteBundleTransfer->getProductBundle()
                    ->getBundledProducts()
                    ->getIterator()
                    ->current()
                    ->getIdProductConcrete()
            )
            ->addIdBundledProduct(
                $secondProductConcreteBundleTransfer->getProductBundle()
                    ->getBundledProducts()
                    ->getIterator()
                    ->current()
                    ->getIdProductConcrete()
            )
            ->setApplyGrouped(true);

        // Act
        $productBundleTransfers = $this->tester->getFacade()
            ->getProductBundleCollectionByCriteriaFilter($productBundleCriteriaFilterTransfer)
            ->getProductBundles();

        // Assert
        $this->assertCount(2, $productBundleTransfers);
        $this->assertCount(
            1,
            $productBundleTransfers->getIterator()->offsetGet(0)->getBundledProducts()
        );
        $this->assertCount(
            1,
            $productBundleTransfers->getIterator()->offsetGet(1)->getBundledProducts()
        );
    }

    /**
     * @return void
     */
    public function testGetProductBundleCollectionByCriteriaFilterWithIsActiveFilter(): void
    {
        // Arrange
        $firstProductConcreteBundleTransfer = $this->tester->haveProductBundle(
            $this->tester->haveFullProduct(['isActive' => false])
        );

        $secondProductConcreteBundleTransfer = $this->tester->haveProductBundle($this->tester->haveFullProduct());

        $productBundleCriteriaFilterTransfer = (new ProductBundleCriteriaFilterTransfer())
            ->addIdProductConcrete($firstProductConcreteBundleTransfer->getIdProductConcrete())
            ->addIdProductConcrete($secondProductConcreteBundleTransfer->getIdProductConcrete())
            ->setApplyGrouped(true)
            ->setIsProductConcreteActive(true)
            ->setIsBundledProductActive(true);

        // Act
        $productBundleTransfers = $this->tester->getFacade()
            ->getProductBundleCollectionByCriteriaFilter($productBundleCriteriaFilterTransfer)
            ->getProductBundles();

        // Assert
        $this->assertCount(1, $productBundleTransfers);
    }
}
