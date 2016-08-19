<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Braintree\Business;

use Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionRequestLog;
use Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionStatusLog;
use Spryker\Zed\Braintree\Business\Payment\Method\ApiConstants;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Braintree
 * @group Business
 * @group BraintreeFacadeConditionsTest
 */
class BraintreeFacadeConditionsTest extends AbstractFacadeTest
{

    /**
     * @var \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionStatusLog
     */
    protected $transactionStatusLogEntity;

    /**
     * @var \Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeTransactionRequestLog
     */
    protected $transactionRequestLogEntity;

    /**
     * @return void
     */
    public function testIsAuthorizationApproved()
    {
        $this->setUpAuthorizationTestData();

        $orderTransfer = $this->createOrderTransfer();
        $facade = $this->getFacade();
        $response = $facade->isAuthorizationApproved($orderTransfer);
        $this->assertTrue($response);
    }

    /**
     * @return void
     */
    public function testIsCaptureApproved()
    {
        $this->setUpCaptureTestData();

        $orderTransfer = $this->createOrderTransfer();
        $facade = $this->getFacade();
        $response = $facade->isCaptureApproved($orderTransfer);
        $this->assertTrue($response);
    }

    /**
     * @return void
     */
    public function testIsReversalApproved()
    {
        $this->setUpReversalTestData();

        $orderTransfer = $this->createOrderTransfer();
        $facade = $this->getFacade();
        $response = $facade->isReversalApproved($orderTransfer);
        $this->assertTrue($response);
    }

    /**
     * @return void
     */
    public function testIsRefundApproved()
    {
        $this->setUpRefundTestData();

        $orderTransfer = $this->createOrderTransfer();
        $facade = $this->getFacade();
        $response = $facade->isRefundApproved($orderTransfer);
        $this->assertTrue($response);
    }

    /**
     * @return void
     */
    private function setUpAuthorizationTestData()
    {
        $this->transactionRequestLogEntity = (new SpyPaymentBraintreeTransactionRequestLog())
            ->setFkPaymentBraintree($this->paymentEntity->getIdPaymentBraintree())
            ->setTransactionType('sale')
            ->setTransactionCode(ApiConstants::TRANSACTION_CODE_AUTHORIZE)
            ->setTransactionId('abc');
        $this->transactionRequestLogEntity->save();

        $this->transactionStatusLogEntity = (new SpyPaymentBraintreeTransactionStatusLog())
            ->setFkPaymentBraintree($this->paymentEntity->getIdPaymentBraintree())
            ->setCode('1000')
            ->setIsSuccess(true)
            ->setMessage('Approved')
            ->setTransactionType('sale')
            ->setTransactionCode(ApiConstants::TRANSACTION_CODE_AUTHORIZE)
            ->setTransactionStatus(ApiConstants::STATUS_CODE_AUTHORIZE)
            ->setTransactionId('abc');
        $this->transactionStatusLogEntity->save();
    }

    /**
     * @return void
     */
    private function setUpCaptureTestData()
    {
        $this->transactionRequestLogEntity = (new SpyPaymentBraintreeTransactionRequestLog())
            ->setFkPaymentBraintree($this->paymentEntity->getIdPaymentBraintree())
            ->setTransactionType('sale')
            ->setTransactionCode(ApiConstants::TRANSACTION_CODE_CAPTURE)
            ->setTransactionId('c');
        $this->transactionRequestLogEntity->save();

        $this->transactionStatusLogEntity = (new SpyPaymentBraintreeTransactionStatusLog())
            ->setFkPaymentBraintree($this->paymentEntity->getIdPaymentBraintree())
            ->setCode('1000')
            ->setIsSuccess(true)
            ->setMessage('Approved')
            ->setTransactionType('sale')
            ->setTransactionCode(ApiConstants::TRANSACTION_CODE_CAPTURE)
            ->setTransactionStatus(ApiConstants::STATUS_CODE_CAPTURE)
            ->setTransactionId('c');
        $this->transactionStatusLogEntity->save();
    }

    /**
     * @return void
     */
    private function setUpReversalTestData()
    {
        $this->transactionRequestLogEntity = (new SpyPaymentBraintreeTransactionRequestLog())
            ->setFkPaymentBraintree($this->paymentEntity->getIdPaymentBraintree())
            ->setTransactionType('sale')
            ->setTransactionCode(ApiConstants::TRANSACTION_CODE_REVERSAL)
            ->setTransactionId('r');
        $this->transactionRequestLogEntity->save();

        $this->transactionStatusLogEntity = (new SpyPaymentBraintreeTransactionStatusLog())
            ->setFkPaymentBraintree($this->paymentEntity->getIdPaymentBraintree())
            ->setCode('1000')
            ->setIsSuccess(true)
            ->setMessage('Approved')
            ->setTransactionType('sale')
            ->setTransactionCode(ApiConstants::TRANSACTION_CODE_REVERSAL)
            ->setTransactionStatus(ApiConstants::STATUS_CODE_REVERSAL)
            ->setTransactionId('r');
        $this->transactionStatusLogEntity->save();
    }

    /**
     * @return void
     */
    private function setUpRefundTestData()
    {
        $this->transactionRequestLogEntity = (new SpyPaymentBraintreeTransactionRequestLog())
            ->setFkPaymentBraintree($this->paymentEntity->getIdPaymentBraintree())
            ->setTransactionType('credit')
            ->setTransactionCode(ApiConstants::TRANSACTION_CODE_REFUND)
            ->setTransactionId('ref');
        $this->transactionRequestLogEntity->save();

        $this->transactionStatusLogEntity = (new SpyPaymentBraintreeTransactionStatusLog())
            ->setFkPaymentBraintree($this->paymentEntity->getIdPaymentBraintree())
            ->setCode('1000')
            ->setIsSuccess(true)
            ->setMessage('Approved')
            ->setTransactionType('credit')
            ->setTransactionCode(ApiConstants::TRANSACTION_CODE_REFUND)
            ->setTransactionStatus(ApiConstants::STATUS_CODE_REFUND)
            ->setTransactionId('ref');
        $this->transactionStatusLogEntity->save();
    }

}
