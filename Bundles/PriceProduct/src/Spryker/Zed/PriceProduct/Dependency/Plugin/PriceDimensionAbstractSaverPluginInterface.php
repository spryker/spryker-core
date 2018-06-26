<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Dependency\Plugin;

use Generated\Shared\Transfer\PriceProductTransfer;

interface PriceDimensionAbstractSaverPluginInterface
{
    /**
     * Specification:
     *  - Saves price for selected price dimension
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function savePrice(PriceProductTransfer $priceProductTransfer): PriceProductTransfer;

    /**
     * Specification:
     *  - Returns dimension name for matching price dimension before saving
     *
     * @api
     *
     * @return string
     */
    public function getDimensionName(): string;
}
