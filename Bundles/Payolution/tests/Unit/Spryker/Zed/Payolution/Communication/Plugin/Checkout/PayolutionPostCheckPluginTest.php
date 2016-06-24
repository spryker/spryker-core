<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */


namespace Unit\Spryker\Zed\Payolution\Communication\Plugin;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLog;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLogQuery;
use Spryker\Zed\Payolution\Business\Payment\Method\ApiConstants;
use Spryker\Zed\Payolution\Communication\Plugin\Checkout\PayolutionPostCheckPlugin;
use Spryker\Zed\Payolution\Persistence\PayolutionQueryContainerInterface;

/**
 * @group Spryker
 * @group Zed
 * @group Payolution
 * @group Communication
 * @group PayolutionPostCheckPlugin
 */
class PayolutionPostCheckPluginTest extends \PHPUnit_Framework_TestCase
{

    const PROCESSING_SUCCESS_CODE = 'VA.PA.90';
    const PROCESSING_ERROR_CODE = 'error code';

    /**
     * @return void
     */
    public function testExecuteWithApprovedTransactionShouldNotAddErrorTransferToCheckoutResponseTransfer()
    {
        $transactionStatusLogEntity = $this->getTransactionStatusLogEntity();
        $transactionStatusLogEntity->setProcessingCode(self::PROCESSING_SUCCESS_CODE);
        $queryContainerMock = $this->getQueryContainerMock($transactionStatusLogEntity);

        $postCheckPlugin = new PayolutionPostCheckPlugin($queryContainerMock);

        $checkoutResponseTransfer = $this->getCheckoutResponseTransfer();
        $postCheckPlugin->execute(new QuoteTransfer(), $checkoutResponseTransfer);

        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testExecuteWithNotApprovedTransactionShouldAddErrorTransferToCheckoutResponseTransfer()
    {
        $transactionStatusLogEntity = $this->getTransactionStatusLogEntity();
        $transactionStatusLogEntity->setProcessingCode(self::PROCESSING_ERROR_CODE);
        $queryContainerMock = $this->getQueryContainerMock($transactionStatusLogEntity);

        $postCheckPlugin = new PayolutionPostCheckPlugin($queryContainerMock);

        $checkoutResponseTransfer = $this->getCheckoutResponseTransfer();
        $postCheckPlugin->execute(new QuoteTransfer(), $checkoutResponseTransfer);

        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $expectedError = $checkoutResponseTransfer->getErrors()[0];

        $this->assertSame(ApiConstants::CHECKOUT_ERROR_CODE_PAYMENT_FAILED, $expectedError->getErrorCode());
    }

    /**
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLog
     */
    private function getTransactionStatusLogEntity()
    {
        return new SpyPaymentPayolutionTransactionStatusLog();
    }

    /**
     * @param \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLog $transactionStatusLogEntity
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Payolution\Persistence\PayolutionQueryContainerInterface
     */
    private function getQueryContainerMock(SpyPaymentPayolutionTransactionStatusLog $transactionStatusLogEntity)
    {
        $queryContainerMock = $this->getMock(PayolutionQueryContainerInterface::class);
        $transactionStatusLogQueryMock = $this->getTransactionStatusLogQueryMock($transactionStatusLogEntity);
        $queryContainerMock->method('queryTransactionStatusLogBySalesOrderId')->willReturn($transactionStatusLogQueryMock);

        return $queryContainerMock;
    }

    /**
     * @param \Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLog $transactionStatusLogEntity
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionTransactionStatusLogQuery
     */
    private function getTransactionStatusLogQueryMock(SpyPaymentPayolutionTransactionStatusLog $transactionStatusLogEntity)
    {
        $transactionStatusLogQueryMock = $this->getMock(SpyPaymentPayolutionTransactionStatusLogQuery::class);
        $transactionStatusLogQueryMock->method('findOne')->willReturn($transactionStatusLogEntity);

        return $transactionStatusLogQueryMock;
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    private function getCheckoutResponseTransfer()
    {
        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $checkoutResponseTransfer->setIsSuccess(true);

        $saveOrderTransfer = new SaveOrderTransfer();
        $saveOrderTransfer->setIdSalesOrder(23);

        $checkoutResponseTransfer->setSaveOrder($saveOrderTransfer);

        return $checkoutResponseTransfer;
    }

}
