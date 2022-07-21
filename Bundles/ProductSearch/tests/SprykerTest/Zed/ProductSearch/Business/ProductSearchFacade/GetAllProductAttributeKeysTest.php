<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSearch\Business\ProductSearchFacade;

use Codeception\Test\Unit;
use SprykerTest\Zed\ProductSearch\ProductSearchBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductSearch
 * @group Business
 * @group ProductSearchFacade
 * @group GetAllProductAttributeKeysTest
 *
 * Add your own group annotations below this line
 */
class GetAllProductAttributeKeysTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductSearch\ProductSearchBusinessTester
     */
    protected ProductSearchBusinessTester $tester;

    /**
     * @return void
     */
    public function testGetAllProductAttributeKeysShouldReturnAttributeKeys(): void
    {
        // Arrange
        $this->tester->ensureProductAttributeKeyTableIsEmpty();

        $expectedProductAttributeKeys = [];
        $expectedCount = 3;
        for ($i = 0; $i < $expectedCount; $i++) {
            $expectedProductAttributeKeys[] = $this->tester->haveProductAttributeKeyEntity()->getKey();
        }

        // Act
        $productAttributeKeys = $this->tester->getFacade()->getAllProductAttributeKeys();

        // Assert
        $this->assertCount($expectedCount, $productAttributeKeys);
        $this->assertEmpty(array_diff($expectedProductAttributeKeys, $productAttributeKeys));
    }
}
