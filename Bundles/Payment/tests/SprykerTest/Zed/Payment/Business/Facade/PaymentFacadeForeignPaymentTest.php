<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Payment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AddPaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Zed\Payment\Business\PaymentFacade;
use SprykerTest\Zed\Payment\PaymentBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Payment
 * @group Business
 * @group Facade
 * @group Facade
 * @group PaymentFacadeForeignPaymentTest
 * Add your own group annotations below this line
 */
class PaymentFacadeForeignPaymentTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Payment\PaymentBusinessTester
     */
    protected PaymentBusinessTester $tester;

    /**
     * Tests the UpdatePaymentMethod message.
     *
     * @return void
     */
    public function testGivenThePaymentMethodAlreadyExistsAndIsActiveWhenTheUpdatePaymentMethodMessageIsHandledThenThePaymentMethodIsUpdated(): void
    {
        // Arrange
        $paymentMethodName = 'MethodName-' . Uuid::uuid4()->toString();
        $paymentProviderKey = 'ProviderKey-' . Uuid::uuid4()->toString();

        $paymentMethodTransfer = $this->tester->havePaymentMethodWithPaymentProviderPersisted([
            PaymentProviderTransfer::PAYMENT_PROVIDER_KEY => $paymentProviderKey,
            PaymentMethodTransfer::IS_ACTIVE => true,
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => (new PaymentFacade())->generatePaymentMethodKey($paymentProviderKey, $paymentMethodName),
            PaymentMethodTransfer::NAME => $paymentMethodName,
            PaymentMethodTransfer::IS_FOREIGN => true,
            PaymentMethodTransfer::PAYMENT_AUTHORIZATION_ENDPOINT => 'before-change',
        ]);

        $updatePaymentMethodTransfer = $this->tester->haveUpdatePaymentMethodTransfer([
            AddPaymentMethodTransfer::NAME => $paymentMethodName,
            AddPaymentMethodTransfer::PROVIDER_NAME => $paymentProviderKey,
            AddPaymentMethodTransfer::PAYMENT_AUTHORIZATION_ENDPOINT => 'after-change',
        ]);

        // Act
        $this->tester->getFacade()->consumePaymentMethodMessage($updatePaymentMethodTransfer);

        // Assert
        $updatedPaymentMethodTransfer = $this->tester->findPaymentMethodById($paymentMethodTransfer->getIdPaymentMethod());

        $this->assertFalse($updatedPaymentMethodTransfer->getIsHidden(), 'Expected that the payment method is visible but it is hidden');
        $this->assertTrue($updatedPaymentMethodTransfer->getIsActive(), 'Expected that the payment method is active but it is inactive');
        $this->assertSame('after-change', $updatedPaymentMethodTransfer->getPaymentAuthorizationEndpoint(), 'Expected that the payment authorization endpoint is updated but it is not');
    }
}
