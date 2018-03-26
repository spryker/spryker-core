<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer;

use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\Offer\OfferConfig getSharedConfig()
 */
class OfferConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getOrderTypeOffer(): string
    {
        return $this->getSharedConfig()->getOrderTypeOffer();
    }

    /**
     * @return null|string
     */
    public function getOrderTypeDefault(): ?string
    {
       return $this->getSharedConfig()->getOrderTypeDefault();
    }
}
