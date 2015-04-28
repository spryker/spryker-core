<?php

namespace SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\AbstractPaymentMethodContainer;


class CashOnDeliveryContainer extends AbstractPaymentMethodContainer
{

    /**
     * @var string
     */
    protected $shippingprovider;

    /**
     * @param string $shippingprovider
     */
    public function setShippingProvider($shippingprovider)
    {
        $this->shippingprovider = $shippingprovider;
    }

    /**
     * @return string
     */
    public function getShippingProvider()
    {
        return $this->shippingprovider;
    }

}
