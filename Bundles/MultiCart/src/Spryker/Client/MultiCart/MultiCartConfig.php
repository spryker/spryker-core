<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart;

use Spryker\Client\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\MultiCart\MultiCartConfig getSharedConfig()
 */
class MultiCartConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getCustomerQuoteDefaultName()
    {
        return $this->getSharedConfig()->getCustomerQuoteDefaultName();
    }

    /**
     * @return string
     */
    public function getGuestQuoteDefaultName()
    {
        return $this->getSharedConfig()->getGuestQuoteDefaultName();
    }

    /**
     * @return string
     */
    public function getDuplicatedQuoteNameSuffix()
    {
        return $this->getSharedConfig()->getDuplicatedQuoteNameSuffix();
    }
}
