<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfigurationCart\Business\ProductConfigurationCartFacade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductConfigurationCart
 * @group Business
 * @group ProductConfigurationCartFacade
 * @group ExpandProductConfigurationItemsWithGroupKeyTest
 * Add your own group annotations below this line
 */
class ExpandProductConfigurationItemsWithGroupKeyTest extends Unit
{
    /**
     * @var array
     */
    protected const TEST_PRODUCT_CONFIGURATION_ARRAY = ['test_group_key'];

    /**
     * @var string
     */
    protected const TEST_PRODUCT_CONFIGURATION_HASH = '0146dbdb9eb9a1d17dc66478f869f556';

    /**
     * @var string
     */
    protected const TEST_GROUP_KEY = 'test_group_key';

    /**
     * @var \SprykerTest\Zed\ProductConfigurationCart\ProductConfigurationCartBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testWillExpandItemGroupKeyWithProductConfigurationHash(): void
    {
        // Arrange
        $productConfigurationInstanceMock = $this->getMockBuilder(ProductConfigurationInstanceTransfer::class)
            ->onlyMethods(['toArray'])
            ->getMock();

        $productConfigurationInstanceMock->method('toArray')->willReturn(static::TEST_PRODUCT_CONFIGURATION_ARRAY);

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::GROUP_KEY => static::TEST_GROUP_KEY,
            ItemTransfer::PRODUCT_CONFIGURATION_INSTANCE => $productConfigurationInstanceMock,
        ]))->build();

        $cartChangeTransfer = (new CartChangeTransfer())->addItem($itemTransfer);

        $itemProductConfigurationGroupKey = sprintf(
            '%s-%s',
            $itemTransfer->getGroupKey(),
            static::TEST_PRODUCT_CONFIGURATION_HASH,
        );

        // Act
        $expandedCartChangeTransfer = $this->tester->getFacade()
            ->expandProductConfigurationItemsWithGroupKey($cartChangeTransfer);

        /** @var \Generated\Shared\Transfer\ItemTransfer $expandedItemTransfer */
        $expandedItemTransfer = $expandedCartChangeTransfer->getItems()->getIterator()->current();

        // Assert
        $this->assertSame(
            $itemProductConfigurationGroupKey,
            $expandedItemTransfer->getGroupKey(),
            'Expects that item group key will be expanded with product configuration hash.',
        );
    }

    /**
     * @return void
     */
    public function testWillNotExpandItemGroupKeyWithoutProductConfiguration(): void
    {
        // Arrange
        $itemTransfer = (new ItemBuilder([
            ItemTransfer::GROUP_KEY => static::TEST_GROUP_KEY,
        ]))->build();

        $cartChangeTransfer = (new CartChangeTransfer())->addItem($itemTransfer);

        // Act
        $expandedCartChangeTransfer = $this->tester->getFacade()
            ->expandProductConfigurationItemsWithGroupKey($cartChangeTransfer);

        /** @var \Generated\Shared\Transfer\ItemTransfer $expandedItemTransfer */
        $expandedItemTransfer = $expandedCartChangeTransfer->getItems()->getIterator()->current();

        // Assert
        $this->assertSame(
            static::TEST_GROUP_KEY,
            $expandedItemTransfer->getGroupKey(),
            'Expects that item group key will not be expanded with product configuration hash.',
        );
    }
}
