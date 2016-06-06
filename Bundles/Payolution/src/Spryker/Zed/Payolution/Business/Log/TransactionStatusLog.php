<?php

/**
 * This file is part of the Spryker Platform.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Payolution\Business\Log;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Payolution\Business\Payment\Method\ApiConstants;
use Spryker\Zed\Payolution\Persistence\PayolutionQueryContainerInterface;

class TransactionStatusLog implements TransactionStatusLogInterface
{

    /**
     * @var \Spryker\Zed\Payolution\Persistence\PayolutionQueryContainerInterface
     */
    private $queryContainer;

    /**
     * @param \Spryker\Zed\Payolution\Persistence\PayolutionQueryContainerInterface $queryContainer
     */
    public function __construct(PayolutionQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPreAuthorizationApproved(OrderTransfer $orderTransfer)
    {
        return $this->hasTransactionLogStatus(
            $orderTransfer,
            ApiConstants::PAYMENT_CODE_PRE_AUTHORIZATION,
            ApiConstants::STATUS_REASON_CODE_SUCCESS
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isReAuthorizationApproved(OrderTransfer $orderTransfer)
    {
        return $this->hasTransactionLogStatus(
            $orderTransfer,
            ApiConstants::PAYMENT_CODE_RE_AUTHORIZATION,
            ApiConstants::STATUS_REASON_CODE_SUCCESS
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isReversalApproved(OrderTransfer $orderTransfer)
    {
        return $this->hasTransactionLogStatus(
            $orderTransfer,
            ApiConstants::PAYMENT_CODE_REVERSAL,
            ApiConstants::STATUS_REASON_CODE_SUCCESS
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isCaptureApproved(OrderTransfer $orderTransfer)
    {
        return $this->hasTransactionLogStatus(
            $orderTransfer,
            ApiConstants::PAYMENT_CODE_CAPTURE,
            ApiConstants::STATUS_REASON_CODE_SUCCESS
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundApproved(OrderTransfer $orderTransfer)
    {
        return $this->hasTransactionLogStatus(
            $orderTransfer,
            ApiConstants::PAYMENT_CODE_REFUND,
            ApiConstants::STATUS_REASON_CODE_SUCCESS
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string $paymentCode
     * @param string $expectedStatusReasonCode
     *
     * @return bool
     */
    private function hasTransactionLogStatus(OrderTransfer $orderTransfer, $paymentCode, $expectedStatusReasonCode)
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
