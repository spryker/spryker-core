<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductsRestApi\Communication\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\RestCartItemsAttributesTransfer;
use Spryker\Glue\MerchantProductsRestApi\Plugin\CartsRestApi\MerchantProductCartItemExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductsRestApi
 * @group Communication
 * @group Plugin
 * @group MerchantProductCartItemExpanderPluginTest
 * Add your own group annotations below this line
 */
class MerchantProductCartItemExpanderPluginTest extends Unit
{
    protected const TEST_MERCHANT_REFERENCE = 'test-merchant-reference';

    /**
     * @var \SprykerTest\Zed\MerchantProductsRestApi\MerchantProductsRestApiCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandPopulatesRequestWithMerchantReference(): void
    {
        // Arrange
        $merchantProductCartItemExpanderPlugin = new MerchantProductCartItemExpanderPlugin();

        // Act
        $cartItemRequestTransfer = $merchantProductCartItemExpanderPlugin->expand(
            new CartItemRequestTransfer(),
            (new RestCartItemsAttributesTransfer())
                ->setMerchantReference(static::TEST_MERCHANT_REFERENCE)
        );

        // Assert
        $this->assertSame($cartItemRequestTransfer->getMerchantReference(), static::TEST_MERCHANT_REFERENCE);
    }
}
