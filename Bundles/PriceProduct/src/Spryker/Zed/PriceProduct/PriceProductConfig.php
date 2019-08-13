<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct;

use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\PriceProduct\PriceProductConfig getSharedConfig()
 */
class PriceProductConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getPriceTypeDefaultName()
    {
        return $this->getSharedConfig()->getPriceTypeDefaultName();
    }

    /**
     * @return string
     */
    public function getPriceDimensionDefault()
    {
        return $this->getSharedConfig()->getPriceDimensionDefault();
    }

    /**
     * @return string
     */
    public function getPriceModeIdentifierForBothType()
    {
        return $this->getSharedConfig()->getPriceModeIdentifierForBothType();
    }

    /**
     * @return string
     */
    public function getPriceDimensionDefaultName(): string
    {
        return $this->getSharedConfig()->getPriceDimensionDefaultName();
    }

    /**
     * @return bool
     */
    public function getDeleteOrphanPricesModeEnabled(): bool
    {
        return $this->getSharedConfig()->getDeleteOrphanPricesModeEnabled();
    }
}
