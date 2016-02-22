<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
