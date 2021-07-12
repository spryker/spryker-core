<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferExtension\Dependency\Plugin;

use Generated\Shared\Transfer\PriceProductTransfer;

/**
 * Provides PriceProductTransfer extending after data is fetched from persistence layer.
 */
interface PriceProductOfferExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands PriceProductTransfer with additional data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function expand(PriceProductTransfer $priceProductTransfer): PriceProductTransfer;
}
