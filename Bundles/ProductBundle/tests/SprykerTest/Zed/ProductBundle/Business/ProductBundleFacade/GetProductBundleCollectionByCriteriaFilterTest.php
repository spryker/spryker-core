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
        //Assign
        $productConcreteBundleTransfer = $this->tester->haveProductBundle($this->tester->haveFullProduct());

        $productBundleCriteriaFilterTransfer = (new ProductBundleCriteriaFilterTransfer())
            ->addIdProductConcrete($productConcreteBundleTransfer->getIdProductConcrete());

        //Act
        $productConcreteBundleTransfers = $this->tester->getFacade()
            ->getProductBundleCollectionByCriteriaFilter($productBundleCriteriaFilterTransfer)
            ->getProductBundles();

        //Assert
        $this->assertCount(3, $productConcreteBundleTransfers);
        $this->assertEquals($productConcreteBundleTransfers->offsetGet(0), $productConcreteBundleTransfers->offsetGet(1));
    }

    /**
     * @return void
     */
    public function testGetProductBundleCollectionByCriteriaFilterWithFakeProductConcreteIdFilter(): void
    {
        //Assign
        $productBundleCriteriaFilterTransfer = (new ProductBundleCriteriaFilterTransfer())
            ->addIdProductConcrete(static::FAKE_ID_PRODUCT_CONCRETE);

        //Act
        $productConcreteBundleTransfers = $this->tester->getFacade()
            ->getProductBundleCollectionByCriteriaFilter($productBundleCriteriaFilterTransfer)
            ->getProductBundles();

        //Assert
        $this->assertEmpty($productConcreteBundleTransfers);
    }

    /**
     * @return void
     */
    public function testGetProductBundleCollectionByCriteriaFilterWithLimit(): void
    {
        //Assign
        $productBundleCriteriaFilterTransfer = (new ProductBundleCriteriaFilterTransfer())
            ->setFilter((new FilterTransfer())->setLimit(1));

        //Act
        $productConcreteBundleTransfers = $this->tester->getFacade()
            ->getProductBundleCollectionByCriteriaFilter($productBundleCriteriaFilterTransfer)
            ->getProductBundles();

        //Assert
        $this->assertCount(1, $productConcreteBundleTransfers);
    }

    /**
     * @return void
     */
    public function testGetProductBundleCollectionByCriteriaFilterWithGroupedFilter(): void
    {
        //Assign
        $productConcreteBundleTransfer = $this->tester->haveProductBundle($this->tester->haveFullProduct());

        $productBundleCriteriaFilterTransfer = (new ProductBundleCriteriaFilterTransfer())
            ->addIdProductConcrete($productConcreteBundleTransfer->getIdProductConcrete())
            ->setApplyGrouped(true);

        //Act
        $productConcreteBundleTransfers = $this->tester->getFacade()
            ->getProductBundleCollectionByCriteriaFilter($productBundleCriteriaFilterTransfer)
            ->getProductBundles();

        //Assert
        $this->assertCount(1, $productConcreteBundleTransfers);
        $this->assertCount(3, $productConcreteBundleTransfers->offsetGet(0)->getBundledProducts());
    }
}
