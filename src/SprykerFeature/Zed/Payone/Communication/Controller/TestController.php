<?php

namespace SprykerFeature\Zed\Payone\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;

class TestController extends AbstractController
{

    public function indexAction()
    {
        $order = $this->getLocator()->sales()->transferOrder();
        $order->setFirstName('horst');
        $order->setLastName('wurst');
        $order->setEmail('horst@wurst.de');
        $order->setIsTest(true);
        $order->setIncrementId('DY999999997');
        $order->setIdSalesOrder(1);
        $order->setSalutation('Mr');

        $totals = $this->getLocator()->calculation()->transferTotals();
        $totals->setGrandTotal(10000);
        //$totals->setTax(300);
        $totals->setSubtotal(10000);

        $order->setTotals($totals);



        $authorization = $this->getLocator()->payone()->transferAuthorization();
        $authorization->setPaymentMethod('payment.payone.prepayment');
        $authorization->setAmount($totals->getGrandTotal());
        $authorization->setReferenceId('DY999999997');

        $authorization->setOrder($order);



        $payoneFacade = $this->getLocator()->payone()->facade();
        $payoneFacade->authorize($authorization);

        die('motherfucker');
    }

    public function preAuthAction()
    {
        $order = $this->getLocator()->sales()->transferOrder();
        $order->setFirstName('horst');
        $order->setLastName('wurst');
        $order->setEmail('horst@wurst.de');
        $order->setIsTest(true);
        $order->setIncrementId('DY999991000');
        $order->setIdSalesOrder(1);
        $order->setSalutation('Mr');

        $totals = $this->getLocator()->calculation()->transferTotals();
        $totals->setGrandTotal(10000);
        //$totals->setTax(300);
        $totals->setSubtotal(10000);

        $order->setTotals($totals);



        $authorization = $this->getLocator()->payone()->transferAuthorization();
        $authorization->setPaymentMethod('payment.payone.prepayment');
        $authorization->setAmount($totals->getGrandTotal());
        $authorization->setReferenceId('DY999991000');

        $authorization->setOrder($order);



        $payoneFacade = $this->getLocator()->payone()->facade();
        $payoneFacade->preAuthorize($authorization);

        die('motherfucker');
    }

    public function captureAction()
    {
        $order = $this->getLocator()->sales()->transferOrder();
        $order->setFirstName('horst');
        $order->setLastName('wurst');
        $order->setEmail('horst@wurst.de');
        $order->setIsTest(true);
        $order->setIncrementId('DY999991000');
        $order->setIdSalesOrder(1);
        $order->setSalutation('Mr');

        $totals = $this->getLocator()->calculation()->transferTotals();
        $totals->setGrandTotal(10000);
        //$totals->setTax(300);
        $totals->setSubtotal(10000);
        $order->setTotals($totals);


        $payment = $this->getLocator()->payone()->transferPayment();
        $payment->setTransactionId('161237526');
        $payment->setPaymentMethod('payment.payone.prepayment');

        $capture = $this->getLocator()->payone()->transferCapture();
        $capture->setPaymentMethod('payment.payone.prepayment');
        $capture->setPayment($payment);
        $capture->setAmount($totals->getGrandTotal());
        $capture->setOrder($order);



        $payoneFacade = $this->getLocator()->payone()->facade();
        $payoneFacade->capture($capture);

        die('motherfucker');
    }

    public function debitAction()
    {
        $order = $this->getLocator()->sales()->transferOrder();
        $order->setFirstName('horst');
        $order->setLastName('wurst');
        $order->setEmail('horst@wurst.de');
        $order->setIsTest(true);
        $order->setIncrementId('DY999991000');
        $order->setIdSalesOrder(1);
        $order->setSalutation('Mr');

        $totals = $this->getLocator()->calculation()->transferTotals();
        $totals->setGrandTotal(10000);
        //$totals->setTax(300);
        $totals->setSubtotal(10000);
        $order->setTotals($totals);


        $payment = $this->getLocator()->payone()->transferPayment();
        $payment->setTransactionId('161237526');
        $payment->setPaymentMethod('payment.payone.prepayment');

        $debit = $this->getLocator()->payone()->transferDebit();
        $debit->setPaymentMethod('payment.payone.prepayment');
        $debit->setPayment($payment);
        $debit->setAmount(1000);
        $debit->setOrder($order);



        $payoneFacade = $this->getLocator()->payone()->facade();
        $payoneFacade->debit($debit);

        die('motherfucker');
    }

    public function myTestAction()
    {
        $payoneFacade = $this->getLocator()->payone()->facade();
        $payoneFacade->myTest();

        die('motherfucker');
    }

}
