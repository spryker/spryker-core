<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Braintree\Business;

use Braintree\Result\Successful;
use Braintree\Transaction;
use Braintree\Transaction\StatusDetails;
use DateTime;
use Generated\Shared\Transfer\RefundTransfer;
use Spryker\Zed\Braintree\BraintreeConfig;
use Spryker\Zed\Braintree\Business\Payment\Method\ApiConstants;
use Spryker\Zed\Braintree\Business\Payment\Transaction\RefundTransaction;
use Spryker\Zed\Braintree\Dependency\Facade\BraintreeToMoneyBridge;
use Spryker\Zed\Braintree\Dependency\Facade\BraintreeToMoneyInterface;
use Spryker\Zed\Braintree\Dependency\Facade\BraintreeToRefundInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Braintree
 * @group Business
 * @group Facade
 * @group BraintreeFacadeRefundTest
 * Add your own group annotations below this line
 */
class BraintreeFacadeRefundTest extends AbstractFacadeTest
{
    /**
     * @return void
     */
    public function testRefundPaymentWithSuccessResponse()
    {
        $factoryMock = $this->getFactoryMock(['createRefundTransaction', 'getRefundFacade']);
        $factoryMock->method('createRefundTransaction')->willReturn(
            $this->getRefundTransactionMock()
        );
        $factoryMock->method('getRefundFacade')->willReturn(
            $this->getRefundFacadeMock()
        );
        $braintreeFacade = $this->getBraintreeFacade($factoryMock);
        $response = $braintreeFacade->refundPayment([], $this->getOrderEntity());

        $this->assertTrue($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testRefundPaymentWithFailureResponse()
    {
        $factoryMock = $this->getFactoryMock(['createRefundTransaction', 'getRefundFacade']);
        $factoryMock->method('createRefundTransaction')->willReturn(
            $this->getRefundTransactionMock(false)
        );
        $factoryMock->method('getRefundFacade')->willReturn(
            $this->getRefundFacadeMock(false)
        );
        $braintreeFacade = $this->getBraintreeFacade($factoryMock);
        $response = $braintreeFacade->refundPayment([], $this->getOrderEntity());

        $this->assertFalse($response->getIsSuccess());
    }

    /**
     * @param bool $success
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Braintree\Business\Payment\Transaction\RefundTransaction
     */
    protected function getRefundTransactionMock($success = true)
    {
        $moneyFacadeMock = $this->getMoneyFacadeMock();
        $refundTransactionMockBuilder = $this->getMockBuilder(RefundTransaction::class);
        $refundTransactionMockBuilder->setMethods(['refund', 'initializeBraintree']);
        $refundTransactionMockBuilder->setConstructorArgs([
            new BraintreeConfig(),
            new BraintreeToMoneyBridge($moneyFacadeMock),
        ]);

        if ($success) {
            $response = $this->getSuccessResponse();
        } else {
            $response = $this->getErrorResponse();
        }

        $refundTransactionMock = $refundTransactionMockBuilder->getMock();
        $refundTransactionMock->expects($this->once())
            ->method('refund')
            ->willReturn($response);

        return $refundTransactionMock;
    }

    /**
     * @return \Braintree\Result\Successful
     */
    protected function getSuccessResponse()
    {
        $transaction = $this->getTransaction(ApiConstants::STATUS_CODE_CAPTURE_SUBMITTED);
        $response = new Successful([$transaction]);

        return $response;
    }

    /**
     * @param bool $success
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Braintree\Dependency\Facade\BraintreeToRefundInterface
     */
    protected function getRefundFacadeMock($success = true)
    {
        $refundFacadeMock = $this->getMockBuilder(BraintreeToRefundInterface::class)->setMethods(['calculateRefund', 'saveRefund'])->getMock();
        $refundFacadeMock->expects($this->any())->method('calculateRefund')->willReturn(new RefundTransfer());
        if ($success) {
            $refundFacadeMock->expects($this->once())->method('saveRefund');
        } else {
            $refundFacadeMock->expects($this->never())->method('saveRefund');
        }

        return $refundFacadeMock;
    }

    /**
     * @param string $status
     *
     * @return \Braintree\Transaction
     */
    protected function getTransaction($status)
    {
        $transaction = Transaction::factory([
            'id' => 123,
            'processorResponseCode' => 1000,
            'processorResponseText' => 'Approved',
            'createdAt' => new DateTime(),
            'status' => $status,
            'type' => 'refund',
            'amount' => 10.00,
            'merchantAccountId' => 'abc',
            'statusHistory' => new StatusDetails([
                'timestamp' => new DateTime(),
                'status' => 'settling',
            ]),
        ]);

        return $transaction;
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
