<?php

namespace SprykerFeature\Zed\Checkout\Communication\Controller;

use Generated\Shared\Transfer\OrderItemTransfer;
use Generated\Shared\Transfer\OrderItemsTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractSdkController;
use SprykerFeature\Shared\ZedRequest\Client\RequestInterface;

use SprykerFeature\Zed\Payone\Business\Model\Api\ApiConstants as PayoneApiConstants;

class SdkController extends AbstractSdkController
{

    const MESSAGE_KEY = 'message';
    const DATA_KEY = 'data';

    /**
     * @param \SprykerFeature\Shared\Sales\Transfer\Order $transferOrder
     * @return \SprykerFeature\Shared\Sales\Transfer\Order
     */
    public function recalculateAction(Order $transferOrder)
    {
        return $this->getCalculationFacade()->recalculate($transferOrder);
    }

    /**
     * @param \SprykerFeature\Shared\Sales\Transfer\Order $transferOrder
     * @param RequestInterface                               $requestTransfer
     * @return \SprykerFeature\Shared\Sales\Transfer\Order
     */
    public function saveOrderAction(Order $transferOrder, RequestInterface $requestTransfer)
    {
        $logContext = array();
        $logContext["module"] = 'SprykerFeature\Zed\Checkout\Communication\Controller';//FIXME
        $logContext["controller"] = "SdkController";
        $logContext["action"] = "saveOrderAction";
        $logContext["params"] = ["ToBeDone"];//FIXME

        $componentResult = $this->getCheckoutFacade()->saveOrder($transferOrder, $requestTransfer, $logContext);

        if (!$componentResult->isSuccess()) {
            $this->setSuccess(false);
            foreach ($componentResult->getErrors() as $error) {
                $this->addErrorMessage($error);
            }
            // on error recalculate to be sane
            return $this->getCalculationFacade()->recalculate($transferOrder);
        } else {
            return $componentResult->getTransfer();
        }
    }

    public function saveOrderTestAction(\SprykerFeature\Shared\Sales\Transfer\Order $transferOrder = null, \SprykerFeature\Shared\Library\Communication\Request $requestTransfer = null)
    {
        $transferOrder = new \Generated\Shared\Transfer\OrderTransfer();
        $transferOrder->setEmail('test+123@spryker.com');

        $address = new \Generated\Shared\Transfer\SalesAddressTransfer();
        $address->setFirstName('Max');
        $address->setLastName('Muster');
        $address->setAddress1('Teststr.');
        $address->setCity('Berlin');
        $address->setIso2Country('DE');
        $address->setZipCode('10435');
        $transferOrder->setBillingAddress($address);
        $transferOrder->setShippingAddress($address);

        $paymentData = (new \SprykerEngine\Shared\Kernel\TransferLocator())->locatePayonePaymentPayone();
        $paymentData->setMethod(PayoneApiConstants::PAYMENT_METHOD_PAYPAL);

        $payment = new \Generated\Shared\Transfer\SalesPaymentTransfer();
        $payment->setPaymentData($paymentData);
        $payment->setMethod(PayoneApiConstants::PAYMENT_METHOD_PAYPAL);
        $transferOrder->setPayment($payment);

        $items = new SalesOrderItemsTransfer();

        $item = new OrderItemTransfer();
        $item->setSku('211709');
        $item->setPriceToPay(10500);
        $item->setName('Gewinde-Aussch.Lehrring D2299M1,2 LMW');
        $item->setGrossPrice(10500);

        $items->addOrderItem($item);

        $item = new OrderItemTransfer();
        $item->setSku('210105');
        $item->setPriceToPay(722);
        $item->setName('Auflageplatte A-SPCN 12');
        $item->setGrossPrice(722);

        $items->addOrderItem($item);

        $item = new OrderItemTransfer();
        $item->setSku('211815');
        $item->setPriceToPay(3940);
        $item->setName('Gewinde-Reparatur-Satz M14 x 1,25 V-Coil');
        $item->setGrossPrice(3940);

        $items->addOrderItem($item);

        $item = new OrderItemTransfer();
        $item->setSku('210105');
        $item->setPriceToPay(722);
        $item->setName('Auflageplatte A-SPCN 12');
        $item->setGrossPrice(722);

        $items->addOrderItem($item);

        $item = new OrderItemTransfer();
        $item->setSku('210403');
        $item->setPriceToPay(26200);
        $item->setName('Aderendhülsensortiment 1250-tlg. 4kt. Knipex');
        $item->setGrossPrice(26200);

        $items->addOrderItem($item);
        $transferOrder->setItems($items);

        $this->saveOrderAction($transferOrder, $requestTransfer);
    }

    /**
     * @return \SprykerFeature\Zed\Calculation\Business\CalculationFacade
     */
    public function getCalculationFacade()
    {
        return $this->getLocator()->calculation()->facade();
    }

    /**
     * @return \SprykerFeature\Zed\Checkout\Business\CheckoutFacade
     */
    public function getCheckoutFacade()
    {
        return $this->getLocator()->checkout()->facade();
    }
}
