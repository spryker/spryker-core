<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteApproval\Communication\Plugin\Permission;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreConditionPluginInterface;
use Spryker\Zed\QuoteApproval\Business\QuoteApprovalFacade;
use Spryker\Zed\QuoteApproval\Communication\Plugin\Checkout\QuoteApprovalCheckoutPreConditionPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group QuoteApproval
 * @group Communication
 * @group Plugin
 * @group Permission
 * @group QuoteApprovalCheckoutPreConditionPluginTest
 * Add your own group annotations below this line
 */
class QuoteApprovalCheckoutPreConditionPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testCheckConditionResponseIsSuccessful(): void
    {
        // Arrange
        $quoteApprovalCheckoutPreConditionPlugin = $this->createApproveQuotePermissionPlugin(false);
        $quoteTransfer = new QuoteTransfer();
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $canProceedCheckout = $quoteApprovalCheckoutPreConditionPlugin->checkCondition($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($canProceedCheckout);
        $this->assertEmpty($checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testCheckConditionResponseIsNotSuccessful(): void
    {
        // Arrange
        $quoteApprovalCheckoutPreConditionPlugin = $this->createApproveQuotePermissionPlugin(true);
        $quoteTransfer = new QuoteTransfer();
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $canProceedCheckout = $quoteApprovalCheckoutPreConditionPlugin->checkCondition($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($canProceedCheckout);
        $this->assertNotEmpty($checkoutResponseTransfer->getErrors());
    }

    /**
     * @param bool $status
     *
     * @return \Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreConditionPluginInterface
     */
    protected function createApproveQuotePermissionPlugin(bool $status): CheckoutPreConditionPluginInterface
    {
        $quoteApprovalFacadeMock = $this->getMockBuilder(QuoteApprovalFacade::class)->getMock();
        $quoteApprovalFacadeMock->method('isQuoteApprovalRequired')->willReturn($status);

        return (new QuoteApprovalCheckoutPreConditionPlugin())->setFacade($quoteApprovalFacadeMock);
    }
}
