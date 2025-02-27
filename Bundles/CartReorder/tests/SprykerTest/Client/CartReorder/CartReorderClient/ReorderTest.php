<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\CartReorder\CartReorderClient;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\CartReorder\CartReorderDependencyProvider;
use Spryker\Client\CartReorderExtension\Dependency\Plugin\CartReorderQuoteProviderStrategyPluginInterface;
use SprykerTest\Client\CartReorder\CartReorderClientTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group CartReorder
 * @group CartReorderClient
 * @group ReorderTest
 * Add your own group annotations below this line
 */
class ReorderTest extends Unit
{
    /**
     * @var \SprykerTest\Client\CartReorder\CartReorderClientTester
     */
    protected CartReorderClientTester $tester;

    /**
     * @return void
     */
    public function testShouldExecuteCartReorderQuoteProviderStrategyPluginStack(): void
    {
        // Assert
        $this->tester->setDependency(
            CartReorderDependencyProvider::PLUGINS_CART_REORDER_QUOTE_PROVIDER_STRATEGY,
            [
                $this->getCartReorderQuoteProviderStrategyPluginMock(),
            ],
        );

        // Arrange
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setCustomerReference('Test')
            ->setOrderReference('Test');

        // Act
        $this->tester->getClient()->reorder($cartReorderRequestTransfer);
    }

    /**
     * @return \Spryker\Client\CartReorderExtension\Dependency\Plugin\CartReorderQuoteProviderStrategyPluginInterface
     */
    protected function getCartReorderQuoteProviderStrategyPluginMock(): CartReorderQuoteProviderStrategyPluginInterface
    {
        $cartReorderQuoteProviderStrategyPluginMock = $this
            ->getMockBuilder(CartReorderQuoteProviderStrategyPluginInterface::class)
            ->getMock();

        $cartReorderQuoteProviderStrategyPluginMock
            ->expects($this->once())
            ->method('execute')
            ->willReturn(new QuoteTransfer());

        $cartReorderQuoteProviderStrategyPluginMock
            ->expects($this->once())
            ->method('isApplicable')
            ->willReturn(true);

        return $cartReorderQuoteProviderStrategyPluginMock;
    }
}
