<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Ratepay\Business\Payment\Handler\Transaction;

use Generated\Shared\Transfer\RatepayInstallmentCalculationResponseTransfer;
use Generated\Shared\Transfer\RatepayInstallmentConfigurationResponseTransfer;
use Generated\Shared\Transfer\RatepayResponseTransfer;
use Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\InitPaymentTransaction;
use Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\InstallmentCalculationTransaction;
use Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\InstallmentConfigurationTransaction;
use Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\RequestPaymentTransaction;
use Spryker\Zed\Ratepay\Business\Request\Payment\Method\Elv;
use Spryker\Zed\Ratepay\Business\Request\Payment\Method\Installment;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group Payment
 * @group Handler
 * @group Transaction
 * @group QuoteTransactionTest
 * Add your own group annotations below this line
 */
class QuoteTransactionTest extends BaseTransactionTest
{
    public const SUCCESS_MESSAGE = 'Die Prüfung war erfolgreich. Vielen Dank, dass Sie die Zahlart Rechnung gewählt haben.';

    /**
     * @return void
     */
    public function testPreAuthorizationApproved()
    {
        self::markTestSkipped();
        $transactionHandler = $this->getTransactionHandlerObject(InitPaymentTransaction::class);

        $this->assertInstanceOf(InitPaymentTransaction::class, $transactionHandler);
    }

    /**
     * @return void
     */
    public function testInitPayment()
    {
        self::markTestSkipped();
        $additionalMockMethods = [
            'getMethodMapper' => $this->mockPaymentMethod(Elv::class),
        ];
        $transactionHandler = $this->getTransactionHandlerObject(InitPaymentTransaction::class, $additionalMockMethods);
        $transactionHandler->registerMethodMapper($this->mockMethodInvoice());

        $ratepayResponseTransfer = $transactionHandler->request($this->mockRatepayPaymentInitTransfer());

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
        self::markTestSkipped();
        $additionalMockMethods = [
            'getMethodMapper' => $this->mockPaymentMethod(Elv::class),
        ];
        $transactionHandler = $this->getTransactionHandlerObject(RequestPaymentTransaction::class, $additionalMockMethods);
        $transactionHandler->registerMethodMapper($this->mockMethodInvoice());

        $ratepayResponseTransfer = $transactionHandler->request($this->mockRatepayPaymentRequestTransfer());

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
        self::markTestSkipped();
        $additionalMockMethods = [
            'getMethodMapper' => $this->mockPaymentMethod(Installment::class),
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
        self::markTestSkipped();
        $additionalMockMethods = [
            'getMethodMapper' => $this->mockPaymentMethod(Installment::class),
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
