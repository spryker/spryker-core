<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPageSearch\Business;

use Codeception\Test\Unit;
use Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPageSearch
 * @group Business
 * @group Facade
 * @group ProductPageSearchFacadeTest
 * Add your own group annotations below this line
 */
class ProductPageSearchFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductPageSearch\ProductPageSearchBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface
     */
    protected $productPageSearchFacade;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->tester->setUp();

        $this->productPageSearchFacade = new ProductPageSearchFacade();
    }

    /**
     * @return void
     */
    public function testUnpublishProductConcretePageSearches(): void
    {
        $productAbstractTransfer = $this->tester->getProductAbstractTransfer();
        $productConcreteTransfer = $this->tester->getProductConcreteTransfer();
        $storeNames = $this->tester->getStoreNames();

        $this->productPageSearchFacade->publishProductConcretePageSearchesByProductAbstractIds([$productAbstractTransfer->getIdProductAbstract()]);

        $productConcretePageSearchTransfers = $this->productPageSearchFacade->getProductConcretePageSearchTransfersByProductIds([$productConcreteTransfer->getIdProductConcrete()]);
        $this->assertEquals(count($storeNames), count($productConcretePageSearchTransfers));

        $productAbstractStoreMap = [
            $productAbstractTransfer->getIdProductAbstract() => [$storeNames[0]],
        ];
        unset($storeNames[0]);
        $this->productPageSearchFacade->unpublishProductConcretePageSearches($productAbstractStoreMap);

        $productConcretePageSearchTransfers = $this->productPageSearchFacade->getProductConcretePageSearchTransfersByProductIds([$productConcreteTransfer->getIdProductConcrete()]);

        $this->assertEquals(count($storeNames), count($productConcretePageSearchTransfers));

        foreach ($productConcretePageSearchTransfers as $productConcretePageSearchTransfer) {
            $this->assertEquals($productConcreteTransfer->getIdProductConcrete(), $productConcretePageSearchTransfer->getFkProduct());
            $this->assertContains($productConcretePageSearchTransfer->getStore(), $storeNames);
        }
    }

    /**
     * @return void
     */
    public function testPublishProductConcretePageSearchesByProductAbstractIds(): void
    {
        $productAbstractTransfer = $this->tester->getProductAbstractTransfer();
        $productConcreteTransfer = $this->tester->getProductConcreteTransfer();
        $storeNames = $this->tester->getStoreNames();

        $this->productPageSearchFacade->publishProductConcretePageSearchesByProductAbstractIds([$productAbstractTransfer->getIdProductAbstract()]);

        $productConcretePageSearchTransfers = $this->productPageSearchFacade->getProductConcretePageSearchTransfersByProductIds([$productConcreteTransfer->getIdProductConcrete()]);

        $this->assertEquals(count($storeNames), count($productConcretePageSearchTransfers));

        foreach ($productConcretePageSearchTransfers as $productConcretePageSearchTransfer) {
            $this->assertEquals($productConcreteTransfer->getIdProductConcrete(), $productConcretePageSearchTransfer->getFkProduct());
            $this->assertContains($productConcretePageSearchTransfer->getStore(), $storeNames);
        }
    }
}
