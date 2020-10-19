<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPageSearch\Persistence;

use Codeception\Test\Unit;
use Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepository;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPageSearch
 * @group Persistence
 * @group Repository
 * @group ProductPageSearchRepositoryTest
 * Add your own group annotations below this line
 */
class ProductPageSearchRepositoryTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductPageSearch\ProductPageSearchPersistenceTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetEligibleForAddToCartProductAbstractsIdsReturnsCorrectProductAbstractIds(): void
    {
        // Arrange
        $productConcreteTransfer1 = $this->tester->haveProduct();
        $productConcreteTransfer2 = $this->tester->haveProduct();
        $productAbstractIds = [
            $productConcreteTransfer1->getFkProductAbstract(),
            $productConcreteTransfer2->getFkProductAbstract(),
        ];

        // Act
        $result = (new ProductPageSearchRepository())->getEligibleForAddToCartProductAbstractsIds($productAbstractIds);

        // Assert
        $this->assertCount(2, $result);
        $this->assertContains($productAbstractIds[0], $result);
        $this->assertContains($productAbstractIds[1], $result);
    }
}
