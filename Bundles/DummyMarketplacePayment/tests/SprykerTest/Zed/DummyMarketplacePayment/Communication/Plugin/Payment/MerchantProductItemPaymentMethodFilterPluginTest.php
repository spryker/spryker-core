<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DummyMarketplacePayment\Communication\Plugin\Payment;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\DummyMarketplacePayment\DummyMarketplacePaymentConfig;
use Spryker\Zed\DummyMarketplacePayment\Communication\Plugin\Payment\MerchantProductItemPaymentMethodFilterPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DummyMarketplacePayment
 * @group Communication
 * @group Plugin
 * @group Payment
 * @group MerchantProductItemPaymentMethodFilterPluginTest
 *
 * Add your own group annotations below this line
 */
class MerchantProductItemPaymentMethodFilterPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_PRODUCT_MERCHANT = 'TEST_PRODUCT_MERCHANT';

    /**
     * @var \SprykerTest\Zed\DummyMarketplacePayment\DummyMarketplacePaymentCommunicationTester
     */
    protected $tester;

    /**
     * @return \Spryker\Zed\DummyMarketplacePayment\Communication\Plugin\Payment\MerchantProductItemPaymentMethodFilterPlugin
     */
    protected function getPlugin(): MerchantProductItemPaymentMethodFilterPlugin
    {
        return new MerchantProductItemPaymentMethodFilterPlugin();
    }

    /**
     * @return void
     */
    public function testFilterPaymentMethodsReturnsMarketplacePaymentWithCorrectData(): void
    {
        // Arrange
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->addItem((new ItemTransfer())->setMerchantReference(static::TEST_PRODUCT_MERCHANT));
        $quoteTransfer->addItem((new ItemTransfer())->setMerchantReference(static::TEST_PRODUCT_MERCHANT));

        $paymentMethod = (new PaymentMethodTransfer())
            ->setPaymentProvider((new PaymentProviderTransfer())->setName(DummyMarketplacePaymentConfig::PAYMENT_PROVIDER_NAME));
        $paymentMethodsTransfer = (new PaymentMethodsTransfer())->addMethod($paymentMethod);

        // Act
        $filteredPaymentMethodsTransfer = $this->getPlugin()->filterPaymentMethods($paymentMethodsTransfer, $quoteTransfer);

        // Assert
        $this->assertCount($paymentMethodsTransfer->getMethods()->count(), $filteredPaymentMethodsTransfer->getMethods());
        $this->assertSame(
            $paymentMethod->getPaymentProvider()->getName(),
            $filteredPaymentMethodsTransfer->getMethods()->offsetGet(0)->getPaymentProvider()->getName(),
        );
    }

    /**
     * @return void
     */
    public function testFilterPaymentMethodsFiltersMarketplacePaymentOutWithIncorrectData(): void
    {
        // Arrange
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->addItem((new ItemTransfer())->setMerchantReference(null));
        $quoteTransfer->addItem((new ItemTransfer())->setMerchantReference(null));

        $paymentMethod = (new PaymentMethodTransfer())
            ->setPaymentProvider((new PaymentProviderTransfer())->setPaymentProviderKey(DummyMarketplacePaymentConfig::PAYMENT_PROVIDER_NAME));
        $paymentMethodsTransfer = (new PaymentMethodsTransfer())->addMethod($paymentMethod);

        // Act
        $filteredPaymentMethodsTransfer = $this->getPlugin()->filterPaymentMethods($paymentMethodsTransfer, $quoteTransfer);

        // Assert
        $this->assertEmpty($filteredPaymentMethodsTransfer->getMethods()->getArrayCopy());
    }
}
