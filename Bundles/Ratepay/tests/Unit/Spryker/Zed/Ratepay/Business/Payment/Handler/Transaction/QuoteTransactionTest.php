<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Ratepay\Business\Payment\Handler\Transaction;

class QuoteTransactionTest extends BaseTransactionTest
{

    public function testPreAuthorizationApproved()
    {
        $transactionHandler = $this->getTransactionHandlerObject('\Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\InitPaymentTransaction');

        $this->assertInstanceOf('\Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\InitPaymentTransaction', $transactionHandler);
    }

    public function testInitPayment()
    {
        $transactionHandler = $this->getTransactionHandlerObject('\Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\InitPaymentTransaction');
        $transactionHandler->registerMethodMapper($this->mockMethodInvoice());

        $ratepayResponseTransfer = $transactionHandler->request($this->mockQuoteTransfer());

        $this->assertInstanceOf('\Generated\Shared\Transfer\RatepayResponseTransfer', $ratepayResponseTransfer);

        $this->assertEquals(
            'Die Prüfung war erfolgreich. Vielen Dank, dass Sie die Zahlart Rechnung gewählt haben.',
            $ratepayResponseTransfer->getCustomerMessage()
        );
    }

    public function testRequestPayment()
    {
        $transactionHandler = $this->getTransactionHandlerObject('\Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\RequestPaymentTransaction');
        $transactionHandler->registerMethodMapper($this->mockMethodInvoice());

        $ratepayResponseTransfer = $transactionHandler->request($this->mockQuoteTransfer());

        $this->assertInstanceOf('\Generated\Shared\Transfer\RatepayResponseTransfer', $ratepayResponseTransfer);

        $this->assertEquals(
            'Die Prüfung war erfolgreich. Vielen Dank, dass Sie die Zahlart Rechnung gewählt haben.',
            $ratepayResponseTransfer->getCustomerMessage()
        );
    }

    public function testInstallmentConfiguration()
    {
        $additionalMockMethods = [
            'getMethodMapper' => $this->mockModelPaymentConfiguration()
        ];
        $transactionHandler = $this->getTransactionHandlerObject(
            '\Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\InstallmentConfigurationTransaction',
            $additionalMockMethods
        );
        $transactionHandler->registerMethodMapper($this->mockMethodInstallmentConfiguration());

        $ratepayResponseTransfer = $transactionHandler->request($this->mockQuoteTransfer('INSTALLMENT'));

        $this->assertInstanceOf('\Generated\Shared\Transfer\RatepayInstallmentConfigurationResponseTransfer', $ratepayResponseTransfer);

        $this->assertEquals(
            'Die Prüfung war erfolgreich. Vielen Dank, dass Sie die Zahlart Rechnung gewählt haben.',
            $ratepayResponseTransfer->getBaseResponse()->getCustomerMessage()
        );
    }

    public function testInstallmentCalculation()
    {
        $additionalMockMethods = [
            'getMethodMapper' => $this->mockModelPaymentCalculation()
        ];

        $transactionHandler = $this->getTransactionHandlerObject(
            '\Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction\InstallmentCalculationTransaction',
            $additionalMockMethods
        );
        $transactionHandler->registerMethodMapper($this->mockMethodInstallmentCalculation());

        $ratepayResponseTransfer = $transactionHandler->request($this->mockQuoteTransfer('INSTALLMENT'));

        $this->assertInstanceOf('\Generated\Shared\Transfer\RatepayInstallmentCalculationResponseTransfer', $ratepayResponseTransfer);

        $this->assertEquals(
            'Die Prüfung war erfolgreich. Vielen Dank, dass Sie die Zahlart Rechnung gewählt haben.',
            $ratepayResponseTransfer->getBaseResponse()->getCustomerMessage()
        );
    }

}
