<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payone\Communication\Controller;

use Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Shared\Payone\PayoneConstants;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Payone\Business\PayoneFacade getFacade()
 * @method \Spryker\Zed\Payone\Communication\PayoneCommunicationFactory getFactory()
 */
class TransactionController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function statusUpdateAction(Request $request)
    {
        //Payone always sends status updates in ISO-8859-1. We transform them to utf8.
        $requestParameters = $request->request->all();

        $map = [
            // TransferObject (internal) => POST request (external)
            'key' => 'key',
            'aid' => 'aid',
            'mode' => 'mode',
            'customerid' => null,
            'portalid' => 'portalid',
            'sequencenumber' => 'sequencenumber',
            'txaction' => 'txaction',
            'receivable' => 'receivable',
            'price' => 'price',
            'balance' => 'balance',
            'currency' => 'currency',
            'txid' => 'txid',
            'userid' => 'userid',
            'txtime' => 'txtime',
            'clearingtype' => 'clearingtype',
            'reference' => 'reference',
            'reminderlevel' => 'reminderlevel',
        ];

        $dataArray = [];
        foreach ($map as $transferObjectKey => $postDataKey) {
            if (!isset($requestParameters[$postDataKey])) {
                continue;
            }
            $dataArray[$transferObjectKey] = utf8_encode($requestParameters[$postDataKey]);
        }

        $payoneTransactionStatusUpdateTransfer = new PayoneTransactionStatusUpdateTransfer();
        $payoneTransactionStatusUpdateTransfer->fromArray($dataArray);

        $response = $this->getFacade()->processTransactionStatusUpdate($payoneTransactionStatusUpdateTransfer);

        $transactionId = $payoneTransactionStatusUpdateTransfer->getTxid();
        $this->triggerEventsOnSuccess($response, $transactionId, $dataArray);

        $callback = function () use ($response) {
            echo $response;
        };

        return $this->streamedResponse($callback);
    }

    /**
     * @param \Spryker\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusResponse $response
     * @param int $transactionId
     * @param array $dataArray
     *
     * @return void
     */
    protected function triggerEventsOnSuccess(TransactionStatusResponse $response, $transactionId, array $dataArray)
    {
        if (!$response->isSuccess()) {
            return;
        }

        //TODO: Refactor as per CD-380
        $orderItems = SpySalesOrderItemQuery::create()
            ->useOrderQuery()
            ->useSpyPaymentPayoneQuery()
            ->filterByTransactionId($transactionId)
            ->endUse()
            ->endUse()
            ->find();
        $this->getFactory()->getOmsFacade()->triggerEvent('PaymentNotificationReceived', $orderItems, []);

        if ($dataArray['txaction'] === PayoneConstants::PAYONE_TXACTION_APPOINTED) {
            $this->getFactory()->getOmsFacade()->triggerEvent('RedirectResponseAppointed', $orderItems, []);
        }
    }

}
