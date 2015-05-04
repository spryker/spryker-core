<?php

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use SprykerFeature\Shared\Library\TransferLoader;
use SprykerFeature\Shared\Sales\Code\Messages;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractSdkController;
use Generated\Shared\Transfer\SalesOrder as OrderTransferTransfer;
use SprykerFeature\Zed\Library\Copy;
use Generated\Shared\Transfer\SalesRegularRedirectPaymentCancellation as RegularRedirectPaymentCancellationTransferTransfer;

class SdkController extends AbstractSdkController
{

    const MESSAGE_KEY = 'message';
    const DATA_KEY = 'data';

    /**
     * @param OrderTransfer $orderTransfer
     * @return null|OrderTransfer
     */
    public function getOrderByIncrementIdAction(\SprykerFeature\Shared\Sales\Transfer\Order $orderTransfer)
    {
        $order = \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderQuery::create()->findOneByIncrementId($orderTransfer->getIncrementId());
        if (empty($order)) {
            $this->setSuccess(false);
            $this->addErrorMessage(Messages::ERROR_ORDER_NOT_FOUND);

            return null;
        }

        return Copy::entityToTransfer(new \Generated\Shared\Transfer\SalesOrderTransfer(), $order, true);
    }

    /**
     * @param RegularRedirectPaymentCancellationTransfer $transfer
     * @return RegularRedirectPaymentCancellationTransfer
     */
    public function setTriggerRegularRedirectPaymentCancellationAction(\SprykerFeature\Shared\Sales\Transfer\RegularRedirectPaymentCancellation $transfer)
    {
        $event = \SprykerFeature_Zed_Sales_Business_Interface_OrderprocessConstant::EVENT_REDIRECT_PAYMENT_CANCELLED_REGULAR;
        $idSalesOrder = $transfer->getOrderId();
        $order = $this->facadeSales->getOrderById($idSalesOrder);
        if (null !== $order) {
            $this->facadeOms->triggerEvent($event, $order->getItems());
        }

        return $transfer;
    }


}
