<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod;

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
