<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMerchantCommissionConnector\Business\Facade;

use Codeception\Test\Unit;
use SprykerTest\Zed\ProductMerchantCommissionConnector\ProductMerchantCommissionConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductMerchantCommissionConnector
 * @group Business
 * @group Facade
 * @group GetProductAttributeKeysTest
 * Add your own group annotations below this line
 */
class GetProductAttributeKeysTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductMerchantCommissionConnector\ProductMerchantCommissionConnectorBusinessTester
     */
    protected ProductMerchantCommissionConnectorBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldReturnAnArrayWithAllProductAttributeKeys(): void
    {
        // Arrange
        $this->tester->ensureProductAttributeKeyTableIsEmpty();

        $productAttributeKey1Entity = $this->tester->haveProductAttributeKeyEntity();
        $productAttributeKey2Entity = $this->tester->haveProductAttributeKeyEntity();
        $expectedProductAttributeKeys = [
            $productAttributeKey1Entity->getKey(),
            $productAttributeKey2Entity->getKey(),
        ];

        // Act
        $productAttributeKeys = $this->tester->getFacade()->getProductAttributeKeys();

        // Assert
        $this->assertCount(0, array_diff($expectedProductAttributeKeys, $productAttributeKeys));
    }
}
