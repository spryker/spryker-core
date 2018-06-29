<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CatalogPriceProductConnector\Dependency;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;

interface CatalogPriceProductConnectorToPriceProductClientInterface
{
    /**
     * Specification:
     *  - Resolves current product price as per current customer state, it will try to resolve price based on customer selected currency and price mode.
     *  - Defaults to price mode defined in environment configuration if customer not yet selected.
     *  - Price map structure: @see original method
     *
     * @api
     *
     * @param array $priceMap
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function resolveProductPrice(array $priceMap);

    /**
     * Specification:
     *  - Resolves current product price as per current customer state, it will try to resolve price based on customer selected currency and price mode.
     *  - Defaults to price mode defined in environment configuration if customer not yet selected.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function resolveProductPriceTransfer(array $priceProductTransfers): CurrentProductPriceTransfer;

    /**
     * Specification:
     *  - Returns default price type as configured for current environment
     *
     * @api
     *
     * @return string
     */
    public function getPriceTypeDefaultName();
}
