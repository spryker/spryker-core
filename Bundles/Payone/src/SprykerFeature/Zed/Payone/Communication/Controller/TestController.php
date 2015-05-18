<?php

namespace SprykerFeature\Zed\Payone\Communication\Controller;

use Generated\Shared\Transfer\TotalsTransfer;
use Generated\Shared\Transfer\AuthorizationTransfer;
use Generated\Shared\Transfer\CaptureTransfer;
use Generated\Shared\Transfer\DebitTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Shared\Payone\PayoneApiConstants;

class TestController extends AbstractController implements PayoneApiConstants
{

    public function prepaymentAction()
    {
        $order = $this->getOrder();

        $authorization = new AuthorizationTransfer();
        $authorization->setPaymentMethod('payment.payone.prepayment');
        $authorization->setAmount($order->getTotals()->getGrandTotal());
        $authorization->setReferenceId($order->getIncrementId());
        $authorization->setOrder($order);

        $this->getLocator()->payone()->facade()->preAuthorize($authorization);

        die('|-o-|');
    }

    public function preAuthAction()
    {
        $order = $this->getOrder();

        $authorization = new AuthorizationTransfer();
        $authorization->setPaymentMethod('payment.payone.prepayment');
        $authorization->setAmount($order->getTotals()->getGrandTotal());
        $authorization->setReferenceId($order->getIncrementId());
        $authorization->setOrder($this->getOrder());

        $this->getLocator()->payone()->facade()->preAuthorize($authorization);

        die('|-o-|');
    }

    public function captureAction()
    {
        $order = $this->getOrder();

        $payment = new PayonePaymentTransfer();
        $payment->setTransactionId('161913038');
        $payment->setPaymentMethod(self::PAYMENT_METHOD_PREPAYMENT);

        $capture = new CaptureTransfer();
        $capture->setPayment($payment);
        $capture->setAmount($order->getTotals()->getGrandTotal());

        $this->getLocator()->payone()->facade()->capture($capture);

        die('|-o-|');
    }

    public function debitAction()
    {
        $payment = new PayonePaymentTransfer();
        $payment->setTransactionId('161237526');
        $payment->setPaymentMethod('payment.payone.prepayment');

        $debit = new DebitTransfer();
        $debit->setPayment($payment);
        $debit->setAmount(1000);

        $this->getLocator()->payone()->facade()->debit($debit);

        die('|-o-|');
    }

    public function checkAuthAction()
    {
        $payment = new PayonePaymentTransfer();
        $payment->setTransactionId('162180281');
        $payment->setPaymentMethod('payment.payone.paypal');
        $payment->setAuthorizationType('preauthorization');


        $result = $this->getLocator()->payone()->facade()->getAuthorizationResponse($payment);

        echo '<pre>' . print_r($result,false) . '</pre>';die;

        die('|-o-|');
    }

    public function paypalAction()
    {
        $order = $this->getOrder();

        $authorization = new AuthorizationTransfer();
        $authorization->setPaymentMethod(self::PAYMENT_METHOD_PAYPAL);
        $authorization->setAmount($order->getTotals()->getGrandTotal());
        $authorization->setReferenceId($order->getIncrementId());

        $authorization->setOrder($order);

        $this->getLocator()->payone()->facade()->preAuthorize($authorization);

        die('|-o-|');
    }

    /**
     * @return OrderTransfer
     */
    protected function getOrder()
    {
        $order = new OrderTransfer();
        $order->setFirstName('horst');
        $order->setLastName('wurst');
        $order->setEmail('horst@wurst.de');
        $order->setIsTest(true);
        $order->setIncrementId('DY999991011');
        $order->setIdSalesOrder(1);
        $order->setSalutation('Mr');

        $totals = new TotalsTransfer();
        $totals->setGrandTotal(10000);
        //$totals->setTax(300);
        $totals->setSubtotal(10000);
        $order->setTotals($totals);

        return $order;
    }

    public function transactionUpdateAction()
    {
        $order = $this->getOrder();

        $params = [
            'aid' => '25811',
            'mode' => 'test',
            'customerid' => '999',
            'key' => hash('md5', 'dFWR8GlNG8aonscn'),
            'portalid' => '2018246',
            'sequencenumber' => '0',
            'txaction' => 'appointed',
            'receivable' => '0',
            'balance' => '0',
            'currency' => 'EUR',
            'txid' => '161913038',
            'userid' => '67518130',
            'txtime' => '1354187955',
            'clearingtype' => 'wlt',
            'reference' => $order->getIncrementId()
        ];


         $r = $this->getLocator()->payone()->facade()->processTransactionStatusUpdate($params);

        echo '<pre>' . print_r($r,false) . '</pre>';die;

        die('|-o-|');
    }

    public function myTestAction()
    {
        $a = ['aa' => 'sjdlfkjsrf',
              'bb' => 45456,
        'cc' => 'sjdflsdjflskdjf'];

        $x = $this->rawResponseFromArray($a);
        \SprykerFeature_Shared_Library_Log::log($x, 'payone-test.log');
        echo '<pre>' . print_r($x,false) . '</pre>';die;

        die('|-o-|');
    }

    protected function rawResponseFromArray(array $request)
    {
        $rawResponse = '';
        $arrayCount = count($request);
        $count = 1;
        foreach ($request as $key => $value) {
            $rawResponse .= $key . '=' . $value;
            if ($count < $arrayCount) {
                $rawResponse .= 'x' . "\n";
            }
            $count++;
        }

        return $rawResponse;
    }


}
