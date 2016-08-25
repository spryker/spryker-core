<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\RatepayInstallmentCalculationResponseTransfer;
use Generated\Shared\Transfer\RatepayInstallmentConfigurationResponseTransfer;
use Generated\Shared\Transfer\RatepayResponseTransfer;
use Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\InitPaymentTransaction;
use Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\InstallmentCalculationTransaction;
use Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\InstallmentConfigurationTransaction;
use Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\RequestPaymentTransaction;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group Payment
 * @group Handler
 * @group Transaction
 * @group QuoteTransactionTest
 */
class QuoteTransactionTest extends BaseTransactionTest
{

    const SUCCESS_MESSAGE = 'Die Prüfung war erfolgreich. Vielen Dank, dass Sie die Zahlart Rechnung gewählt haben.';

    /**
     * @return void
     */
    public function testPreAuthorizationApproved()
    {
        $transactionHandler = $this->getTransactionHandlerObject(InitPaymentTransaction::class);

        $this->assertInstanceOf(InitPaymentTransaction::class, $transactionHandler);
    }

    /**
     * @return void
     */
    public function testInitPayment()
    {
        $transactionHandler = $this->getTransactionHandlerObject(InitPaymentTransaction::class);
        $transactionHandler->registerMethodMapper($this->mockMethodInvoice());

        $ratepayResponseTransfer = $transactionHandler->request($this->mockQuoteTransfer());

        $this->assertInstanceOf(RatepayResponseTransfer::class, $ratepayResponseTransfer);

        $this->assertEquals(
            self::SUCCESS_MESSAGE,
            $ratepayResponseTransfer->getCustomerMessage()
        );
    }

    /**
     * @return void
     */
    public function testRequestPayment()
    {
        $transactionHandler = $this->getTransactionHandlerObject(RequestPaymentTransaction::class);
        $transactionHandler->registerMethodMapper($this->mockMethodInvoice());

        $ratepayResponseTransfer = $transactionHandler->request($this->mockQuoteTransfer());

        $this->assertInstanceOf(RatepayResponseTransfer::class, $ratepayResponseTransfer);

        $this->assertEquals(
            self::SUCCESS_MESSAGE,
            $ratepayResponseTransfer->getCustomerMessage()
        );
    }

    /**
     * @return void
     */
    public function testInstallmentConfiguration()
    {
        $additionalMockMethods = [
            'getMethodMapper' => $this->mockModelPaymentConfiguration()
        ];
        $transactionHandler = $this->getTransactionHandlerObject(InstallmentConfigurationTransaction::class, $additionalMockMethods);
        $transactionHandler->registerMethodMapper($this->mockMethodInstallmentConfiguration());

        $ratepayResponseTransfer = $transactionHandler->request($this->mockQuoteTransfer('INSTALLMENT'));

        $this->assertInstanceOf(RatepayInstallmentConfigurationResponseTransfer::class, $ratepayResponseTransfer);

        $this->assertEquals(
            self::SUCCESS_MESSAGE,
            $ratepayResponseTransfer->getBaseResponse()->getCustomerMessage()
        );
    }

    /**
     * @return void
     */
    public function testInstallmentCalculation()
    {
        $additionalMockMethods = [
            'getMethodMapper' => $this->mockModelPaymentCalculation()
        ];

        $transactionHandler = $this->getTransactionHandlerObject(InstallmentCalculationTransaction::class, $additionalMockMethods);
        $transactionHandler->registerMethodMapper($this->mockMethodInstallmentCalculation());

        $ratepayResponseTransfer = $transactionHandler->request($this->mockQuoteTransfer('INSTALLMENT'));

        $this->assertInstanceOf(RatepayInstallmentCalculationResponseTransfer::class, $ratepayResponseTransfer);

        $this->assertEquals(
            self::SUCCESS_MESSAGE,
            $ratepayResponseTransfer->getBaseResponse()->getCustomerMessage()
        );
    }

}
