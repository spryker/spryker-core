<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConcreteTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Business
 * @group Facade
 * @group ProductFacadeTest
 * Add your own group annotations below this line
 */
class ProductFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Product\ProductBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->tester->setUpDatabase();
    }

    /**
     * @return void
     */
    public function testFindProductConcreteByIds()
    {
        $productConcreteIds = $this->tester->getProductConcreteIds();

        $this->assertTrue(count($productConcreteIds) > 0);

        $productConcreteTransfers = $this->tester->getProductFacade()->findProductConcreteByIds($productConcreteIds);
        $this->assertSame(count($productConcreteIds), count($productConcreteTransfers));

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $this->assertInstanceOf(ProductConcreteTransfer::class, $productConcreteTransfer);
            $this->assertContains($productConcreteTransfer->getIdProductConcrete(), $productConcreteIds);
        }
    }

    /**
     * @return void
     */
    public function testFindAllProductConcrete()
    {
        $productConcreteTransfers = $this->tester->getProductFacade()->findAllProductConcrete();
        $this->assertSame($this->tester->getProductConcreteDatabaseEntriesCount(), count($productConcreteTransfers));
    }
}
