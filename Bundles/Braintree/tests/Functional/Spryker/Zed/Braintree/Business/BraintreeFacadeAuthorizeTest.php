<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Braintree\Business;

use Generated\Shared\Transfer\OrderTransfer;
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
 * @group BraintreeFacadeAuthorizeTest
 */
class BraintreeFacadeAuthorizeTest extends AbstractFacadeTest
{

    /**
     * @return void
     */
    public function testAuthorizePaymentWithSuccessResponse()
    {
        $orderTransfer = $this->createOrderTransfer();

        $idPayment = $this->getPaymentEntity()->getIdPaymentBraintree();
        $facade = $this->getFacadeMockAuthorize($orderTransfer);

        $response = $facade->authorizePayment($orderTransfer, $idPayment);

        $this->assertTrue($response->getIsSuccess());
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Braintree\Business\BraintreeFacade
     */
    private function getFacadeMockAuthorize(OrderTransfer $orderTransfer)
    {
        $facade = new BraintreeFacade();

        $factoryMock = $this->getMock(BraintreeBusinessFactory::class, ['createPaymentTransactionHandler']);

        $queryContainer = new BraintreeQueryContainer();
        $config = new BraintreeConfig();
        $transactionMock = $this->getMock(Transaction::class, ['authorize'], [$queryContainer, $config]);

        $factoryMock->expects($this->once())
            ->method('createPaymentTransactionHandler')
            ->willReturn($transactionMock);

        $transaction = \Braintree\Transaction::factory([
            'processorResponseCode' => '1000',
            'processorResponseText' => 'Approved',
            'createdAt' => new \DateTime(),
            'status' => 'authorized',
            'type' => 'sale',
            'amount' => $orderTransfer->getTotals()->getGrandTotal() / 100,
            'merchantAccountId' => 'abc',
            'statusHistory' => new \Braintree\Transaction\StatusDetails([
                'timestamp' => new \DateTime(),
                'status' => 'authorized'
            ])
        ]);

        $transactionMock->expects($this->once())
            ->method('authorize')
            ->willReturn($transaction);

        $facade->setFactory($factoryMock);

        return $facade;
    }

}
