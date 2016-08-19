<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Braintree\Business;

use Generated\Shared\Transfer\BraintreePaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Braintree\BraintreeConstants;
use Spryker\Zed\Braintree\BraintreeConfig;
use Spryker\Zed\Braintree\Business\BraintreeBusinessFactory;
use Spryker\Zed\Braintree\Business\BraintreeFacade;
use Spryker\Zed\Braintree\Business\Payment\Handler\Transaction\Transaction;
use Spryker\Zed\Braintree\Persistence\BraintreeQueryContainer;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Braintree
 * @group Business
 * @group BraintreePreCheckTest
 */
class BraintreePreCheckTest extends AbstractFacadeTest
{

    /**
     * @return void
     */
    public function testPreCheck()
    {
        $orderTransfer = $this->createOrderTransfer();

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setOrderReference($orderTransfer->getOrderReference());
        $quoteTransfer->setTotals($orderTransfer->getTotals());

        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPaymentSelection(BraintreeConstants::PAYMENT_METHOD_PAY_PAL);

        $paymentTransfer->setBraintree(new BraintreePaymentTransfer());
        $quoteTransfer->setPayment($paymentTransfer);

        $facade = $this->getFacadeMockPreCheck($quoteTransfer);

        $response = $facade->preCheckPayment($quoteTransfer);

        $this->assertTrue($response->getIsSuccess());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Braintree\Business\BraintreeFacade
     */
    private function getFacadeMockPreCheck(QuoteTransfer $quoteTransfer)
    {
        $facade = new BraintreeFacade();

        $factoryMock = $this->getMock(BraintreeBusinessFactory::class, ['createPaymentTransactionHandler']);

        $queryContainer = new BraintreeQueryContainer();
        $config = new BraintreeConfig();
        $transactionMock = $this->getMock(Transaction::class, ['preCheck'], [$queryContainer, $config]);

        $factoryMock->expects($this->once())
            ->method('createPaymentTransactionHandler')
            ->willReturn($transactionMock);

        $response = new \Braintree\Result\Successful();
        $response->transaction = \Braintree\Transaction::factory([
            'id' => 1,
            'paymentInstrumentType' => 'paypal_account',
            'processorSettlementResponseCode' => null,
            'processorResponseCode' => '1000',
            'processorResponseText' => 'Approved',
            'createdAt' => new \DateTime(),
            'status' => 'authorized',
            'type' => 'sale',
            'amount' => $quoteTransfer->getTotals()->getGrandTotal() / 100,
            'merchantAccountId' => 'abc',
            'statusHistory' => new \Braintree\Transaction\StatusDetails([
                'timestamp' => new \DateTime(),
                'status' => 'authorized'
            ]),
            'creditCardDetails' => new \Braintree\Transaction\CreditCardDetails([
                'expirationMonth' => null,
                'expirationYear' => null,
                'bin' => null,
                'last4' => null,
                'cardType' => null,
            ])
        ]);

        $transactionMock->expects($this->once())
            ->method('preCheck')
            ->willReturn($response);

        $facade->setFactory($factoryMock);

        return $facade;
    }

}
