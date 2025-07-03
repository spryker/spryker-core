<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\ProductOffer\Service\Plugin\SalesOrderAmendment;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Service\ProductOffer\Plugin\SalesOrderAmendment\ProductOfferOriginalSalesOrderItemGroupKeyExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group ProductOffer
 * @group Service
 * @group Plugin
 * @group SalesOrderAmendment
 * @group ProductOfferOriginalSalesOrderItemGroupKeyExpanderPluginTest
 * Add your own group annotations below this line
 */
class ProductOfferOriginalSalesOrderItemGroupKeyExpanderPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testShouldDoNothingWhenItemProductOfferReferenceIsNotSet(): void
    {
        // Arrange
        $groupKey = 'sku1';

        // Act
        $expandedGroupKey = (new ProductOfferOriginalSalesOrderItemGroupKeyExpanderPlugin())
            ->expandGroupKey($groupKey, new ItemTransfer());

        // Assert
        $this->assertSame($groupKey, $expandedGroupKey);
    }

    /**
     * @return void
     */
    public function testShouldExpandItemWithProductOfferReferenceWhenItIsSet(): void
    {
        // Arrange
        $groupKey = 'sku1';
        $itemTransfer = (new ItemTransfer())->setProductOfferReference('offer1');

        // Act
        $expandedGroupKey = (new ProductOfferOriginalSalesOrderItemGroupKeyExpanderPlugin())
            ->expandGroupKey($groupKey, $itemTransfer);

        // Assert
        $this->assertSame('sku1_offer1', $expandedGroupKey);
    }
}
