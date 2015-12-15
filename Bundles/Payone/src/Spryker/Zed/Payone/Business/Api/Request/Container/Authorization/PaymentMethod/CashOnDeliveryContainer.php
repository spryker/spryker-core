<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod;

class CashOnDeliveryContainer extends AbstractPaymentMethodContainer
{

    /**
     * @var string
     */
    protected $shippingprovider;

    /**
     * @param string $shippingprovider
     *
     * @return void
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
