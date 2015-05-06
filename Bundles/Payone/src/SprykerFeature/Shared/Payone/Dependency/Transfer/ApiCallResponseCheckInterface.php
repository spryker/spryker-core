<?php

namespace SprykerFeature\Shared\Payone\Dependency\Transfer;


interface ApiCallResponseCheckInterface
{

    /**
     * @param string $requestType
     */
    public function setRequestType($requestType);

    /**
     * @return string
     */
    public function getRequestType();

    /**
     * @param PaymentInterface $payment
     */
    public function setPayment(PaymentInterface $payment);

    /**
     * @return PaymentInterface
     */
    public function getPayment();

}