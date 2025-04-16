<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendment\Communication\Plugin\Cart;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\SalesOrderAmendment\Communication\Plugin\Cart\ResetAmendmentOrderReferencePreReloadItemsPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendment
 * @group Communication
 * @group Plugin
 * @group Cart
 * @group ResetAmendmentOrderReferencePreReloadItemsPluginTest
 * Add your own group annotations below this line
 */
class ResetAmendmentOrderReferencePreReloadItemsPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_ORDER_REFERENCE = 'fake-order-reference';

    /**
     * @return void
     */
    public function testShouldResetAmendmentOrderReference(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::AMENDMENT_ORDER_REFERENCE => static::FAKE_ORDER_REFERENCE,
        ]))->build();

        // Act
        $quoteTransfer = (new ResetAmendmentOrderReferencePreReloadItemsPlugin())->preReloadItems($quoteTransfer);

        // Assert
        $this->assertNull($quoteTransfer->getAmendmentOrderReference());
    }
}
