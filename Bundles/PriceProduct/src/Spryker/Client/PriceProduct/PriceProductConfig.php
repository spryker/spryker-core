<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProduct;

use Spryker\Client\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\PriceProduct\PriceProductConfig getSharedConfig()
 */
class PriceProductConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string
     */
    public function getPriceTypeDefaultName()
    {
        return $this->getSharedConfig()->getPriceTypeDefaultName();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getPriceModeIdentifierForNetType()
    {
        return $this->getSharedConfig()->getPriceModeIdentifierForNetType();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getPriceDimensionDefault(): string
    {
        return $this->getSharedConfig()->getPriceDimensionDefault();
    }
}
