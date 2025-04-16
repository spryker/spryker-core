<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\PriceProductOfferSalesOrderAmendmentConnector\Service\Plugin\PriceProductSalesOrderAmendment;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Service\PriceProductOfferSalesOrderAmendmentConnector\Plugin\PriceProductSalesOrderAmendment\ProductOfferOriginalSalesOrderItemPriceGroupKeyExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group PriceProductOfferSalesOrderAmendmentConnector
 * @group Service
 * @group Plugin
 * @group PriceProductSalesOrderAmendment
 * @group ProductOfferOriginalSalesOrderItemPriceGroupKeyExpanderPluginTest
 * Add your own group annotations below this line
 */
class ProductOfferOriginalSalesOrderItemPriceGroupKeyExpanderPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testShouldDoNothingWhenItemProductOfferReferenceIsNotSet(): void
    {
        // Arrange
        $groupKey = 'sku1';

        // Act
        $expandedGroupKey = (new ProductOfferOriginalSalesOrderItemPriceGroupKeyExpanderPlugin())
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
        $expandedGroupKey = (new ProductOfferOriginalSalesOrderItemPriceGroupKeyExpanderPlugin())
            ->expandGroupKey($groupKey, $itemTransfer);

        // Assert
        $this->assertSame('sku1_offer1', $expandedGroupKey);
    }
}
