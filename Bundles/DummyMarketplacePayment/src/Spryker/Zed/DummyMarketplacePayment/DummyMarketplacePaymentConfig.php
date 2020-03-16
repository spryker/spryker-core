<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DummyMarketplacePayment;

use Spryker\Shared\DummyMarketplacePayment\DummyMarketplacePaymentConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\DummyMarketplacePayment\DummyMarketplacePaymentConfig getSharedConfig()
 */
class DummyMarketplacePaymentConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return array
     */
    public function getDummyMarketplacePaymentMethods(): array
    {
        return $this->get(
            DummyMarketplacePaymentConstants::PAYMENT_METHODS,
            $this->getDummyMarketplaceDefaultPaymentMethods()
        );
    }

    /**
     * @api
     *
     * @return array
     */
    public function getDummyMarketplaceDefaultPaymentMethods(): array
    {
        return [$this->getSharedConfig()::PAYMENT_METHOD_DUMMY_MARKETPLACE_PAYMENT_INVOICE];
    }
}
