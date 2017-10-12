<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Request\Payment\Handler\Transaction;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RatepayResponseTransfer;
use Spryker\Zed\Ratepay\Business\Request\TransactionHandlerAbstract;

abstract class BaseTransaction extends TransactionHandlerAbstract
{
    /**
     * @param int $orderId
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay
     */
    protected function getPaymentMethodByOrderId($orderId)
    {
        return $this->queryContainer
            ->queryPayments()
            ->findByFkSalesOrder($orderId)->getFirst();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay
     */
    protected function getPaymentMethod(OrderTransfer $orderTransfer)
    {
        return $this->getPaymentMethodByOrderId($orderTransfer->requireIdSalesOrder()->getIdSalesOrder());
    }

    /**
     * According to the documentation the transaction ID is always returned, if it was sent, but it is not the fact for
     * error cases, therefore we have to set transaction ID, so it is not lost after each error.
     *
     * @param \Generated\Shared\Transfer\RatepayResponseTransfer $responseTransfer
     * @param string $transId
     * @param string $transShortId
     *
     * @return void
     */
    protected function fixResponseTransferTransactionId(RatepayResponseTransfer $responseTransfer, $transId, $transShortId)
    {
        if ($responseTransfer->getTransactionId() === '' && $transId !== '') {
            $responseTransfer->setTransactionId($transId)->setTransactionShortId($transShortId);
        }
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Base $request
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Response\ResponseInterface $response
     * @param string $method
     * @param int|null $entityId
     * @param array $orderItems
     *
     * @return void
     */
    protected function logInfo($request, $response, $method, $entityId = null, $orderItems = [])
    {
        $headData = $request->getHead()->buildData();

        $context = [
            'order_id' => $entityId,

            'payment_method' => $method,
            'request_type' => static::TRANSACTION_TYPE,
            'request_transaction_id' => (isset($headData['transaction-id'])) ? $headData['transaction-id'] : null,
            'request_transaction_short_id' => (isset($headData['transaction-short-id'])) ? $headData['transaction-short-id'] : null,
            'request_body' => (string)$request,

            'response_type' => $response->getResponseType(),
            'response_result_code' => $response->getResultCode(),
            'response_result_text' => $response->getResultText(),
            'response_transaction_id' => $response->getTransactionId(),
            'response_transaction_short_id' => $response->getTransactionShortId(),
            'response_reason_code' => $response->getReasonCode(),
            'response_reason_text' => $response->getReasonText(),
            'response_status_code' => $response->getStatusCode(),
            'response_status_text' => $response->getStatusText(),
            'response_customer_message' => $response->getCustomerMessage(),

            'item_count' => count($orderItems),
        ];

        $this->getLogger()->info(static::TRANSACTION_TYPE, $context);
    }
}
