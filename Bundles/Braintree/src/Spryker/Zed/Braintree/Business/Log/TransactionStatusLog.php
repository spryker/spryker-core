<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Business\Log;

use Generated\Shared\Transfer\OrderTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Braintree\Business\Payment\Method\ApiConstants;
use Spryker\Zed\Braintree\Persistence\BraintreeQueryContainerInterface;

class TransactionStatusLog implements TransactionStatusLogInterface
{
    /**
     * @var \Spryker\Zed\Braintree\Persistence\BraintreeQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Braintree\Persistence\BraintreeQueryContainerInterface $queryContainer
     */
    public function __construct(BraintreeQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isAuthorizationApproved(OrderTransfer $orderTransfer)
    {
        return $this->hasTransactionStatusLog(
            $orderTransfer,
            ApiConstants::TRANSACTION_CODE_AUTHORIZE,
            ApiConstants::STATUS_CODE_AUTHORIZE,
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
        return $this->hasTransactionStatusLog(
            $orderTransfer,
            ApiConstants::TRANSACTION_CODE_REVERSAL,
            ApiConstants::STATUS_CODE_REVERSAL,
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
        if ($this->hasTransactionStatusLog(
            $orderTransfer,
            ApiConstants::TRANSACTION_CODE_CAPTURE,
            ApiConstants::STATUS_CODE_CAPTURE,
            ApiConstants::STATUS_REASON_CODE_SUCCESS
        )) {
            return true;
        }

        return $this->hasTransactionStatusLog(
            $orderTransfer,
            ApiConstants::TRANSACTION_CODE_CAPTURE,
            ApiConstants::STATUS_CODE_CAPTURE_SUBMITTED,
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
        return $this->hasTransactionStatusLog(
            $orderTransfer,
            ApiConstants::TRANSACTION_CODE_REFUND,
            [ApiConstants::STATUS_CODE_REVERSAL, ApiConstants::STATUS_CODE_REFUND],
            ApiConstants::STATUS_REASON_CODE_SUCCESS
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string $transactionCode
     * @param string|array $statusCode
     * @param string $expectedStatusReasonCode
     *
     * @return bool
     */
    private function hasTransactionStatusLog(OrderTransfer $orderTransfer, $transactionCode, $statusCode, $expectedStatusReasonCode)
    {
        $idSalesOrder = $orderTransfer->getIdSalesOrder();
        $logEntity = $this
            ->queryContainer
            ->queryTransactionStatusLogBySalesOrderIdAndTransactionCodeLatestFirst(
                $idSalesOrder,
                $transactionCode
            )
            ->filterByTransactionStatus((array)$statusCode, Criteria::IN)
            ->findOne();
        if (!$logEntity) {
            return false;
        }

        return $logEntity->getIsSuccess() === (bool)$expectedStatusReasonCode;
    }
}
