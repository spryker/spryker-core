<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\PriceProductSalesOrderAmendment\Communication\Plugin\PriceProductStorage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\PriceProductSalesOrderAmendment\Plugin\PriceProductStorage\OrderItemPriceProductResolveConditionsPriceProductFilterExpanderPlugin;
use SprykerTest\Client\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group PriceProductSalesOrderAmendment
 * @group Communication
 * @group Plugin
 * @group PriceProductStorage
 * @group OrderItemPriceProductResolveConditionsPriceProductFilterExpanderPluginTest
 * Add your own group annotations below this line
 */
class OrderItemPriceProductResolveConditionsPriceProductFilterExpanderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_SKU = 'fake-sku';

    /**
     * @var string
     */
    protected const FAKE_PRODUCT_OFFER_REFERENCE = 'fake-product-offer-reference';

    /**
     * @var \SprykerTest\Client\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentCommunicationTester
     */
    protected PriceProductSalesOrderAmendmentCommunicationTester $tester;

    /**
     * @return void
     */
    public function testShouldExpandPriceProductFilter(): void
    {
        // Arrange
        $productViewTransfer = (new ProductViewTransfer())
            ->setSku(static::FAKE_SKU)
            ->setProductOfferReference(static::FAKE_PRODUCT_OFFER_REFERENCE);

        // Arrange
        $priceProductFilterTransfer = (new OrderItemPriceProductResolveConditionsPriceProductFilterExpanderPlugin())
            ->expand($productViewTransfer, new PriceProductFilterTransfer());

        // Assert
        $this->assertSame(static::FAKE_SKU, $priceProductFilterTransfer->getPriceProductResolveConditions()->getSku());
        $this->assertSame(static::FAKE_PRODUCT_OFFER_REFERENCE, $priceProductFilterTransfer->getPriceProductResolveConditions()->getProductOfferReference());
    }
}
