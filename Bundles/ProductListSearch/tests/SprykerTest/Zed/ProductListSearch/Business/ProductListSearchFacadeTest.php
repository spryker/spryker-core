<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductListSearch\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Generated\Shared\Transfer\ProductListMapTransfer;
use Generated\Shared\Transfer\ProductPageSearchTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductListSearch
 * @group Business
 * @group Facade
 * @group ProductListSearchFacadeTest
 * Add your own group annotations below this line
 */
class ProductListSearchFacadeTest extends Unit
{
    protected const TEST_WHITELIST_KEY = 1;
    protected const TEST_BLACKLIST_KEY = 2;

    /**
     * @var \SprykerTest\Zed\ProductListSearch\ProductListSearchBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandProductConcretePageSearchTransferWithProductLists()
    {
        // Arrange
        $productConcretePageSearchTransfer = new ProductConcretePageSearchTransfer();
        $productConcrete = $this->tester->haveProduct();
        $productConcretePageSearchTransfer->setFkProduct($productConcrete->getIdProductConcrete());

        // Act
        $this->getFacade()->expandProductConcretePageSearchTransferWithProductLists(
            $productConcretePageSearchTransfer
        );

        // Assert
        $this->assertInstanceOf(ProductListMapTransfer::class, $productConcretePageSearchTransfer->getProductListMap());
    }

    /**
     * @return void
     */
    public function testMapProductDataToProductListMapTransfer()
    {
        // Arrange
        $productData = [
            ProductPageSearchTransfer::PRODUCT_LIST_MAP => [
                ProductListMapTransfer::WHITELISTS => [self::TEST_WHITELIST_KEY],
                ProductListMapTransfer::BLACKLISTS => [self::TEST_BLACKLIST_KEY],
            ],
        ];
        $productListMapTransfer = new ProductListMapTransfer();

        // Act
        $this->getFacade()->mapProductDataToProductListMapTransfer($productData, $productListMapTransfer);

        // Assert
        $this->assertIsArray($productListMapTransfer->getWhitelists());
        $this->assertIsArray($productListMapTransfer->getBlacklists());
        $this->assertEquals([self::TEST_WHITELIST_KEY], $productListMapTransfer->getWhitelists());
        $this->assertEquals([self::TEST_BLACKLIST_KEY], $productListMapTransfer->getBlacklists());
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\ProductListSearch\Business\ProductListSearchFacadeInterface
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}
