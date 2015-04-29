<?php

namespace SprykerFeature\Zed\Payone\Business;

use SprykerFeature\Zed\Payone\Business\Mapper\PaymentMethodMapperInterface;


interface PaymentMethodRegistryInterface
{

    /**
     * @param PaymentMethodMapperInterface $paymentMethodMapper
     */
    public function registerPaymentMethodMapper(PaymentMethodMapperInterface $paymentMethodMapper);

    /**
     * @param string $name
     * @return PaymentMethodMapperInterface
     */
    public function findPaymentMethodMapperByName($name);

}