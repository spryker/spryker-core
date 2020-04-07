<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DummyMarketplacePayment\Business;

use Spryker\Zed\DummyMarketplacePayment\Business\Filter\PaymentMethodFilter;
use Spryker\Zed\DummyMarketplacePayment\Business\Filter\PaymentMethodFilterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\DummyMarketplacePayment\DummyMarketplacePaymentConfig getConfig()
 */
class DummyMarketplacePaymentBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\DummyMarketplacePayment\Business\Filter\PaymentMethodFilterInterface
     */
    public function createPaymentMethodFilter(): PaymentMethodFilterInterface
    {
        return new PaymentMethodFilter();
    }
}
