<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Braintree\Business;

use Braintree\Result\Successful;
use Braintree\Transaction;
use Braintree\Transaction\CreditCardDetails;
use Braintree\Transaction\StatusDetails;
use DateTime;
use Generated\Shared\Transfer\BraintreePaymentTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Braintree\BraintreeConstants;
use Spryker\Zed\Braintree\BraintreeConfig;
use Spryker\Zed\Braintree\Business\Payment\Transaction\PreCheckTransaction;
use Spryker\Zed\Braintree\Dependency\Facade\BraintreeToMoneyBridge;
use Spryker\Zed\Braintree\Dependency\Facade\BraintreeToMoneyInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Braintree
 * @group Business
 * @group Facade
 * @group BraintreeFacadePreCheckTest
 * Add your own group annotations below this line
 */
class BraintreeFacadePreCheckTest extends AbstractFacadeTest
{
    /**
     * @return void
     */
    public function testPreCheckPaymentWithSuccessfulResponse()
    {
        $factoryMock = $this->getFactoryMock(['createPreCheckTransaction']);
        $factoryMock->expects($this->once())->method('createPreCheckTransaction')->willReturn(
            $this->getPreCheckTransactionMock()
        );
        $braintreeFacade = $this->getBraintreeFacade($factoryMock);

        $quoteTransfer = $this->getQuoteTransfer();

        $response = $braintreeFacade->preCheckPayment($quoteTransfer);

        $this->assertTrue($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testPreCheckPaymentWithErrorResponse()
    {
        $factoryMock = $this->getFactoryMock(['createPreCheckTransaction']);
        $factoryMock->expects($this->once())->method('createPreCheckTransaction')->willReturn(
            $this->getPreCheckTransactionMock(false)
        );
        $braintreeFacade = $this->getBraintreeFacade($factoryMock);

        $quoteTransfer = $this->getQuoteTransfer();

        $response = $braintreeFacade->preCheckPayment($quoteTransfer);

        $this->assertFalse($response->getIsSuccess());
    }

    /**
     * @param bool $success
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getPreCheckTransactionMock($success = true)
    {
        $moneyFacadeMock = $this->getMoneyFacadeMock();
        $preCheckTransactionMock = $this
            ->getMockBuilder(PreCheckTransaction::class)
            ->setMethods(['preCheck', 'initializeBraintree'])
            ->setConstructorArgs(
                [new BraintreeConfig(), new BraintreeToMoneyBridge($moneyFacadeMock)]
            )
            ->getMock();

        if ($success) {
            $preCheckTransactionMock->expects($this->once())->method('preCheck')->willReturn($this->getSuccessResponse());
        } else {
            $preCheckTransactionMock->expects($this->once())->method('preCheck')->willReturn($this->getErrorResponse());
        }

        return $preCheckTransactionMock;
    }

    /**
     * @return \Braintree\Result\Successful
     */
    protected function getSuccessResponse()
    {
        $transaction = Transaction::factory([
            'id' => 1,
            'paymentInstrumentType' => 'paypal_account',
            'processorSettlementResponseCode' => null,
            'processorResponseCode' => '1000',
            'processorResponseText' => 'Approved',
            'createdAt' => new DateTime(),
            'status' => 'authorized',
            'type' => 'sale',
            'amount' => $this->createOrderTransfer()->getTotals()->getGrandTotal() / 100,
            'merchantAccountId' => 'abc',
            'statusHistory' => new StatusDetails([
                'timestamp' => new DateTime(),
                'status' => 'authorized',
            ]),
            'creditCardDetails' => new CreditCardDetails([
                'expirationMonth' => null,
                'expirationYear' => null,
                'bin' => null,
                'last4' => null,
                'cardType' => null,
            ]),
        ]);
        $response = new Successful($transaction);

        return $response;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getQuoteTransfer()
    {
        $orderTransfer = $this->createOrderTransfer();

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setCustomer(new CustomerTransfer());
        $quoteTransfer->setBillingAddress($orderTransfer->getBillingAddress());
        $quoteTransfer->setShippingAddress($orderTransfer->getShippingAddress());
        $quoteTransfer->setOrderReference($orderTransfer->getOrderReference());
        $quoteTransfer->setTotals($orderTransfer->getTotals());

        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPaymentSelection(BraintreeConstants::PAYMENT_METHOD_PAY_PAL);

        $braintreeTransfer = new BraintreePaymentTransfer();
        $braintreeTransfer->setNonce('fake_valid_nonce');
        $paymentTransfer->setBraintree($braintreeTransfer);

        $quoteTransfer->setPayment($paymentTransfer);

        return $quoteTransfer;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMoneyFacadeMock()
    {
        $moneyFacadeMock = $this->getMockBuilder(BraintreeToMoneyInterface::class)->getMock();

        return $moneyFacadeMock;
    }
}
