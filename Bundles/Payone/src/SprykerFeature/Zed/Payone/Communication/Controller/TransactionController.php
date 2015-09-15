<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Communication\Controller;

use Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Payone\Business\PayoneFacade;
use SprykerFeature\Zed\Payone\Communication\PayoneDependencyContainer;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method PayoneFacade getFacade()
 * @method PayoneDependencyContainer getDependencyContainer()
 */
class TransactionController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return StreamedResponse
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

        if ($response->isSuccess()) {
            //TODO: Refactor as per CD-380
            $orderItems = SpySalesOrderItemQuery::create()
                ->useOrderQuery()
                ->useSpyPaymentPayoneQuery()
                ->filterByTransactionId($payoneTransactionStatusUpdateTransfer->getTxid())
                ->endUse()
                ->endUse()
                ->find();
            $this->getDependencyContainer()->getOmsFacade()->triggerEvent('PaymentNotificationReceived', $orderItems, []);

            if ($dataArray['txaction'] === 'appointed') {
                $this->getDependencyContainer()->getOmsFacade()->triggerEvent('RedirectResponseAppointed', $orderItems, []);
            }
        }

        $callback = function () use ($response) {
            echo $response;
        };

        return $this->streamedResponse($callback);
    }

}
