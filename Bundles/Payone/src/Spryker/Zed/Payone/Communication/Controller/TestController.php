<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payone\Communication\Controller;

use Generated\Shared\Transfer\AuthorizationTransfer;
use Generated\Shared\Transfer\CaptureTransfer;
use Generated\Shared\Transfer\CreditCardTransfer;
use Generated\Shared\Transfer\DebitTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer;
use Generated\Shared\Transfer\PersonalDataTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Shared\Payone\PayoneApiConstants;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\Payone\Business\PayoneFacade getFacade()
 * @method \Spryker\Zed\Payone\Persistence\PayoneQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Payone\Communication\PayoneCommunicationFactory getFactory()
 */
class TestController extends AbstractController
{

    const TEST_TRANSACTION_ID = '165145481';
    const TEST_VISA_PAN = '4111111111111111';
    const TEST_PSEUDO_PAN = '4100000145859436';

    /**
     * @return void
     */
    public function vorPreAuthAction()
    {
        $order = $this->getOrder();

        $authorization = new AuthorizationTransfer();
        $authorization->setPaymentMethod(PayoneApiConstants::PAYMENT_METHOD_PREPAYMENT);
        $authorization->setAmount($order->getTotals()->getGrandTotal());
        $authorization->setReferenceId($order->getIdSalesOrder());
        $authorization->setOrder($order);

        $result = $this->getFacade()->preAuthorize($authorization);

        dump($result);
        die;
    }

    /**
     * @return void
     */
    public function vorAuthAction()
    {
        $order = $this->getOrder();

        $authorization = new AuthorizationTransfer();
        $authorization->setPaymentMethod(PayoneApiConstants::PAYMENT_METHOD_PREPAYMENT);
        $authorization->setAmount($order->getTotals()->getGrandTotal());
        $authorization->setReferenceId($order->getIdSalesOrder());
        $authorization->setOrder($order);

        $result = $this->getFacade()->preAuthorize($authorization);

        dump($result);
        die;
    }

    /**
     * @return void
     */
    public function vorCaptureAction()
    {
        $order = $this->getOrder();

        $payment = new PayonePaymentTransfer();
        $payment->setTransactionId(PayoneApiConstants::TEST_TRANSACTION_ID);
        $payment->setPaymentMethod(PayoneApiConstants::PAYMENT_METHOD_PREPAYMENT);

        $capture = new CaptureTransfer();
        $capture->setPayment($payment);
        $capture->setAmount($order->getTotals()->getGrandTotal());

        $result = $this->getFacade()->capture($capture);

        dump($result);
        die;
    }

    /**
     * @return void
     */
    public function vorDebitAction()
    {
        $payment = new PayonePaymentTransfer();
        $payment->setTransactionId(PayoneApiConstants::TEST_TRANSACTION_ID);
        $payment->setPaymentMethod(PayoneApiConstants::PAYMENT_METHOD_PREPAYMENT);

        $debit = new DebitTransfer();
        $debit->setPayment($payment);
        $debit->setAmount(-100);

        $result = $this->getFacade()->debit($debit);

        dump($result);
        die;
    }

    /**
     * @return void
     */
    public function vorRefundAction()
    {
        $payment = new PayonePaymentTransfer();
        $payment->setTransactionId(PayoneApiConstants::TEST_TRANSACTION_ID);
        $payment->setPaymentMethod(PayoneApiConstants::PAYMENT_METHOD_PREPAYMENT);

        $refund = new RefundTransfer();
        $refund->setPayment($payment);
        $refund->setAmount(-100);

        $result = $this->getFacade()->refundPayment($refund);

        dump($result);
        die;
    }

    /**
     * @return void
     */
    public function ccClientCheckAction()
    {
        $payment = new PayonePaymentTransfer();
        $payment->setPaymentMethod(PayoneApiConstants::PAYMENT_METHOD_CREDITCARD_PSEUDO);

        $creditCard = new CreditCardTransfer();
        $creditCard->setPayment($payment);
        $creditCard->setCardPan(PayoneApiConstants::TEST_VISA_PAN);
        $creditCard->setCardType(PayoneApiConstants::CREDITCARD_TYPE_VISA);
        $creditCard->setCardExpireDate('2012');
//        $creditCard->setCardCvc2('123');
        $creditCard->setStoreCardData('yes');

        $result = $this->getFacade()->creditCardCheck($creditCard);

        dump($result);
        die;
    }

    /**
     * @return void
     */
    public function ccPreAuthAction()
    {
        $order = $this->getOrder();

        $personalData = new PersonalDataTransfer();
        $personalData->setPseudoCardPan(PayoneApiConstants::TEST_PSEUDO_PAN);
        $personalData->setCountry('DE');

        $authorization = new AuthorizationTransfer();
        $authorization->setPaymentMethod(PayoneApiConstants::PAYMENT_METHOD_CREDITCARD_PSEUDO);
        $authorization->setPersonalData($personalData);
        $authorization->setAmount($order->getTotals()->getGrandTotal());
        $authorization->setReferenceId($order->getIdSalesOrder());
        $authorization->setOrder($order);

        $result = $this->getFacade()->preAuthorize($authorization);

        dump($result);
        die;
    }

    /**
     * @return void
     */
    public function ccCheckAuthAction()
    {
        $payment = new PayonePaymentTransfer();
        $payment->setTransactionId(PayoneApiConstants::TEST_TRANSACTION_ID);
        $payment->setPaymentMethod(PayoneApiConstants::PAYMENT_METHOD_CREDITCARD_PSEUDO);
        $payment->setAuthorizationType(PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION);

        $result = $this->getFacade()->getAuthorizationResponse($payment);

        dump($result);
        die;
    }

    /**
     * @return void
     */
    public function ccAuthAction()
    {
        $order = $this->getOrder();

        $personalData = new PersonalDataTransfer();
        $personalData->setPseudoCardPan(PayoneApiConstants::TEST_PSEUDO_PAN);
        $personalData->setCountry('DE');

        $authorization = new AuthorizationTransfer();
        $authorization->setPaymentMethod(PayoneApiConstants::PAYMENT_METHOD_CREDITCARD_PSEUDO);
        $authorization->setPersonalData($personalData);
        $authorization->setAmount($order->getTotals()->getGrandTotal());
        $authorization->setReferenceId($order->getIdSalesOrder());
        $authorization->setOrder($order);

        $result = $this->getFacade()->authorize($authorization);

        dump($result);
        die;
    }

    /**
     * @return void
     */
    public function ccCaptureAction()
    {
        $order = $this->getOrder();

        $payment = new PayonePaymentTransfer();
        $payment->setTransactionId(PayoneApiConstants::TEST_TRANSACTION_ID);
        $payment->setPaymentMethod(PayoneApiConstants::PAYMENT_METHOD_PREPAYMENT);

        $capture = new CaptureTransfer();
        $capture->setPayment($payment);
        $capture->setAmount(1);
        $capture->setAmount($order->getTotals()->getGrandTotal());

        $result = $this->getFacade()->capture($capture);

        dump($result);
        die;
    }

    /**
     * @return void
     */
    public function ccDebitAction()
    {
        $payment = new PayonePaymentTransfer();
        $payment->setTransactionId(PayoneApiConstants::TEST_TRANSACTION_ID);
        $payment->setPaymentMethod(PayoneApiConstants::PAYMENT_METHOD_CREDITCARD_PSEUDO);

        $debit = new DebitTransfer();
        $debit->setPayment($payment);
        $debit->setAmount(-10);

        $result = $this->getFacade()->debit($debit);

        dump($result);
        die;
    }

    /**
     * @return void
     */
    public function ccRefundAction()
    {
        $payment = new PayonePaymentTransfer();
        $payment->setTransactionId(PayoneApiConstants::TEST_TRANSACTION_ID);
        $payment->setPaymentMethod(PayoneApiConstants::PAYMENT_METHOD_CREDITCARD_PSEUDO);

        $refund = new RefundTransfer();
        $refund->setPayment($payment);
        $refund->setAmount(-100);

        $refund->setUseCustomerdata(PayoneApiConstants::USE_CUSTOMER_DATA_YES);
        $refund->setNarrativeText('Test narrative');
//        echo '<pre>' . var_dump($refund) . '</pre>';die;

        $result = $this->getFacade()->refundPayment($refund);

        dump($result);
        die;
    }

    /**
     * @return void
     */
    public function ppCheckAuthAction()
    {
        $payment = new PayonePaymentTransfer();
        $payment->setTransactionId(PayoneApiConstants::TEST_TRANSACTION_ID);
        $payment->setPaymentMethod(PayoneApiConstants::PAYMENT_METHOD_PAYPAL);
        $payment->setAuthorizationType(PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION);

        $result = $this->getFacade()->getAuthorizationResponse($payment);

        dump($result);
        die;
    }

    /**
     * @return void
     */
    public function ppPreAuthAction()
    {
        $order = $this->getOrder();

        $authorization = new AuthorizationTransfer();
        $authorization->setPaymentMethod(PayoneApiConstants::PAYMENT_METHOD_PAYPAL);
        $authorization->setAmount($order->getTotals()->getGrandTotal());
        $authorization->setReferenceId($order->getIdSalesOrder());

        $authorization->setOrder($order);

        $result = $this->getFacade()->preAuthorize($authorization);
        header('Location: ' . $result->getRedirecturl());

        dump($result);
        die;
    }

    /**
     * @return void
     */
    public function ppAuthAction()
    {
        $order = $this->getOrder();

        $authorization = new AuthorizationTransfer();
        $authorization->setPaymentMethod(PayoneApiConstants::PAYMENT_METHOD_PAYPAL);
        $authorization->setAmount($order->getTotals()->getGrandTotal());
        $authorization->setReferenceId($order->getIdSalesOrder());

        $authorization->setOrder($order);

        $result = $this->getFacade()->authorize($authorization);
        header('Location: ' . $result->getRedirecturl());

        dump($result);
        die;
    }

    /**
     * @return void
     */
    public function ppCaptureAction()
    {
        $order = $this->getOrder();

        $payment = new PayonePaymentTransfer();
        $payment->setTransactionId(PayoneApiConstants::TEST_TRANSACTION_ID);
        $payment->setPaymentMethod(PayoneApiConstants::PAYMENT_METHOD_PAYPAL);

        $capture = new CaptureTransfer();
        $capture->setPayment($payment);
        $capture->setAmount($order->getTotals()->getGrandTotal());

        $result = $this->getFacade()->capture($capture);

        dump($result);
        die;
    }

    /**
     * @return void
     */
    public function ppRefundAction()
    {
        $order = $this->getOrder();

        $payment = new PayonePaymentTransfer();
        $payment->setTransactionId(PayoneApiConstants::TEST_TRANSACTION_ID);
        $payment->setPaymentMethod(PayoneApiConstants::PAYMENT_METHOD_PAYPAL);

        $capture = new CaptureTransfer();
        $capture->setPayment($payment);
        $capture->setAmount($order->getTotals()->getGrandTotal());

        $result = $this->getFacade()->capture($capture);

        dump($result);
        die;
    }

    /**
     * @return void
     */
    public function giropayPreAuthAction()
    {
        $order = $this->getOrder();

        $authorization = new AuthorizationTransfer();
        $authorization->setPaymentMethod(PayoneApiConstants::PAYMENT_METHOD_GIROPAY);
        $authorization->setAmount($order->getTotals()->getGrandTotal());
        $authorization->setReferenceId($order->getIdSalesOrder());
        $authorization->setOrder($order);

        $result = $this->getFacade()->preAuthorize($authorization);

        dump($result);
        die;
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrder()
    {
        $order = new OrderTransfer();
        $order->setFirstName('horst');
        $order->setLastName('wurst');
        $order->setEmail('horst@wurst.de');
        $order->setIsTest(true);
//        $order->setIncrementId('DY999991011');

        $order->setOrderReference(rand(0, 100000));

        $order->setIdSalesOrder(1);
        $order->setSalutation('Mr');

        $totals = new TotalsTransfer();
        $totals->setGrandTotal(10000);
        //$totals->setTax(300);
        $totals->setSubtotal(10000);
        $order->setTotals($totals);

        return $order;
    }

    /**
     * @return void
     */
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
            'txid' => PayoneApiConstants::TEST_TRANSACTION_ID,
            'userid' => '67518130',
            'txtime' => '1354187955',
            'clearingtype' => 'wlt',
            'reference' => $order->getOrderReference(),
        ];

        $transactionStatusUpdateTransfer = new PayoneTransactionStatusUpdateTransfer();
        $transactionStatusUpdateTransfer->fromArray($params);

        $r = $this->getFacade()->processTransactionStatusUpdate($transactionStatusUpdateTransfer);

        echo '<pre>' . print_r($r, false) . '</pre>';
        die;
    }

    /**
     * @return void
     */
    public function getPaymentStatus()
    {
        $order = $this->getOrder();

        $result = $this->getFacade()->getPaymentStatus($order);

        dump($result);
        die;
    }

    /**
     * @param array $request
     *
     * @return string
     */
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
