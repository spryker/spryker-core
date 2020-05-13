<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business\ProductBundleFacade;

use Codeception\Test\Unit;
use SprykerTest\Zed\ProductBundle\ProductBundleBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundle
 * @group Business
 * @group ProductBundleFacade
 * @group ExpandItemProductBundlesWithProductOptionsTest
 * Add your own group annotations below this line
 */
class ExpandItemProductBundlesWithProductOptionsTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductBundle\ProductBundleBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandItemProductBundlesWithProductOptionsCopiesUniqueProductOptionsFromItemsToBundleItems(): void
    {
        // Arrange
        $itemTransfers = $this->tester->createBundleItemsWithOptions();

        // Act
        $itemTransfers = $this->tester->getFacade()->expandItemProductBundlesWithProductOptions($itemTransfers);

        // Assert
        $this->assertCount(2, $itemTransfers[0]->getProductBundle()->getProductOptions());
        $this->assertCount(2, $itemTransfers[2]->getProductBundle()->getProductOptions());
    }

    /**
     * @return void
     */
    public function testExpandItemProductBundlesWithProductOptionsConsiderProductOptionOrdering(): void
    {
        // Arrange
        $itemTransfers = $this->tester->createBundleItemsWithOptions();

        // Act
        $itemTransfers = $this->tester->getFacade()->expandItemProductBundlesWithProductOptions($itemTransfers);

        // Assert
        $this->assertSame(
            ProductBundleBusinessTester::FAKE_PRODUCT_OPTION_SKU_1,
            $itemTransfers[2]->getProductBundle()->getProductOptions()->getIterator()->offsetGet(0)->getSku()
        );
    }
}
