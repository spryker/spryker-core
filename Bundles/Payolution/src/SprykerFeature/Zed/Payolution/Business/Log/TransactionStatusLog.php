<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Log;

use Generated\Shared\Payolution\OrderInterface;
use SprykerFeature\Zed\Payolution\Business\Payment\Method\ApiConstants;
use SprykerFeature\Zed\Payolution\Persistence\PayolutionQueryContainerInterface;

class TransactionStatusLog implements TransactionStatusLogInterface
{

    /**
     * @var PayolutionQueryContainerInterface
     */
    private $queryContainer;

    /**
     * @param PayolutionQueryContainerInterface $queryContainer
     */
    public function __construct(PayolutionQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isPreAuthorizationApproved(OrderInterface $orderTransfer)
    {
        return $this->hasTransactionLogStatus(
            $orderTransfer,
            ApiConstants::PAYMENT_CODE_PRE_AUTHORIZATION,
            ApiConstants::STATUS_REASON_CODE_SUCCESS
        );
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isReAuthorizationApproved(OrderInterface $orderTransfer)
    {
        return $this->hasTransactionLogStatus(
            $orderTransfer,
            ApiConstants::PAYMENT_CODE_RE_AUTHORIZATION,
            ApiConstants::STATUS_REASON_CODE_SUCCESS
        );
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isReversalApproved(OrderInterface $orderTransfer)
    {
        return $this->hasTransactionLogStatus(
            $orderTransfer,
            ApiConstants::PAYMENT_CODE_REVERSAL,
            ApiConstants::STATUS_REASON_CODE_SUCCESS
        );
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isCaptureApproved(OrderInterface $orderTransfer)
    {
        return $this->hasTransactionLogStatus(
            $orderTransfer,
            ApiConstants::PAYMENT_CODE_CAPTURE,
            ApiConstants::STATUS_REASON_CODE_SUCCESS
        );
    }

    /**
     * @param OrderInterface $orderTransfer
     *
     * @return bool
     */
    public function isRefundApproved(OrderInterface $orderTransfer)
    {
        return $this->hasTransactionLogStatus(
            $orderTransfer,
            ApiConstants::PAYMENT_CODE_REFUND,
            ApiConstants::STATUS_REASON_CODE_SUCCESS
        );
    }

    /**
     * @param OrderInterface $orderTransfer
     * @param string $paymentCode
     * @param string $expectedStatusReasonCode
     *
     * @return bool
     */
    private function hasTransactionLogStatus(OrderInterface $orderTransfer, $paymentCode, $expectedStatusReasonCode)
    {
        $idSalesOrder = $orderTransfer->getIdSalesOrder();

        $logEntity = $this
            ->queryContainer
            ->queryTransactionStatusLogBySalesOrderIdAndPaymentCodeLatestFirst(
                $idSalesOrder,
                $paymentCode
            )
            ->findOne();

        if (!$logEntity) {
            return false;
        }

        $expectedProcessingCode = $paymentCode . '.' . $expectedStatusReasonCode;

        return ($expectedProcessingCode === $logEntity->getProcessingCode());
    }

}
