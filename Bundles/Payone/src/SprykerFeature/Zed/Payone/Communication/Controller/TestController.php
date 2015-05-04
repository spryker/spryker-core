<?php

namespace SprykerFeature\Zed\Payone\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Shared\Payone\PayoneApiConstants;

class TestController extends AbstractController implements PayoneApiConstants
{

    public function prepaymentAction()
    {
        $order = $this->getOrder();

        $authorization = $this->getLocator()->payone()->transferAuthorization();
        $authorization->setPaymentMethod('payment.payone.prepayment');
        $authorization->setAmount($order->getTotals()->getGrandTotal());
        $authorization->setReferenceId($order->getIncrementId());
        $authorization->setOrder($order);

        $this->getLocator()->payone()->facade()->authorize($authorization);

        die('|-o-|');
    }

    public function preAuthAction()
    {
        $order = $this->getOrder();

        $authorization = $this->getLocator()->payone()->transferAuthorization();
        $authorization->setPaymentMethod('payment.payone.prepayment');
        $authorization->setAmount($order->getTotals()->getGrandTotal());
        $authorization->setReferenceId($order->getIncrementId());
        $authorization->setOrder($this->getOrder());

        $this->getLocator()->payone()->facade()->preAuthorize($authorization);

        die('-o-');
    }

    public function captureAction()
    {
        $order = $this->getOrder();

        $payment = $this->getLocator()->payone()->transferPayment();
        $payment->setTransactionId('161237526');
        $payment->setPaymentMethod('payment.payone.prepayment');

        $capture = $this->getLocator()->payone()->transferCapture();
        $capture->setPaymentMethod('payment.payone.prepayment');
        $capture->setPayment($payment);
        $capture->setAmount($order->getTotals()->getGrandTotal());
        $capture->setOrder($order);

        $this->getLocator()->payone()->facade()->capture($capture);

        die('|-o-|');
    }

    public function debitAction()
    {
        $payment = $this->getLocator()->payone()->transferPayment();
        $payment->setTransactionId('161237526');
        $payment->setPaymentMethod('payment.payone.prepayment');

        $debit = $this->getLocator()->payone()->transferDebit();
        $debit->setPaymentMethod('payment.payone.prepayment');
        $debit->setPayment($payment);
        $debit->setAmount(1000);

        $this->getLocator()->payone()->facade()->debit($debit);

        die('|-o-|');
    }

    public function paypalAction()
    {
        $order = $this->getOrder();

        $authorization = $this->getLocator()->payone()->transferAuthorization();
        $authorization->setPaymentMethod(self::PAYMENT_METHOD_PAYPAL);
        $authorization->setAmount($order->getTotals()->getGrandTotal());
        $authorization->setReferenceId($order->getIncrementId());

        $authorization->setOrder($order);

        $this->getLocator()->payone()->facade()->authorize($authorization);

        die('|-o-|');
    }

    /**
     * @return \SprykerFeature\Shared\Sales\Transfer\Order
     */
    protected function getOrder()
    {
        $order = $this->getLocator()->sales()->transferOrder();
        $order->setFirstName('horst');
        $order->setLastName('wurst');
        $order->setEmail('horst@wurst.de');
        $order->setIsTest(true);
        $order->setIncrementId('DY999991005');
        $order->setIdSalesOrder(1);
        $order->setSalutation('Mr');

        $totals = $this->getLocator()->calculation()->transferTotals();
        $totals->setGrandTotal(10000);
        //$totals->setTax(300);
        $totals->setSubtotal(10000);
        $order->setTotals($totals);

        return $order;
    }

    public function myTestAction()
    {
        $payoneFacade = $this->getLocator()->payone()->facade();
        $payoneFacade->myTest();

        die('|-o-|');
    }

}
