<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCart;

use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\MultiCart\MultiCartConfig getSharedConfig()
 */
class MultiCartConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getCustomerQuoteDefaultName(): string
    {
        return $this->getSharedConfig()->getCustomerQuoteDefaultName();
    }

    /**
     * @return string
     */
    public function getGuestQuoteDefaultName(): string
    {
        return $this->getSharedConfig()->getGuestQuoteDefaultName();
    }
}
