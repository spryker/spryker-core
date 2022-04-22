<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\PriceProductTableCriteriaTransfer;

/**
 * Implement this plugin to filter PriceProduct transfer collection.
 */
interface PriceProductTableFilterPluginInterface
{
    /**
     * Specification:
     * - Filters `PriceProductTransfer` collection.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function filter(array $priceProductTransfers, PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer): array;
}
