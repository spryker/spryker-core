<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProduct;

/**
 * @method \Spryker\Client\PriceProduct\PriceProductFactory getFactory()
 * @method \Spryker\Client\PriceProduct\PriceProductConfig getConfig()
 */
interface PriceProductClientInterface
{
    /**
     * Specification:
     *  - Returns default price type as configured for current environment
     *
     * @api
     *
     * @return string
     */
    public function getPriceTypeDefaultName();

    /**
     * Specification:
     *  - Resolves current product price as per current customer state, it will try to resolve price based on customer selected currency and price mode.
     *  - Defaults to price mode defined in environment configuration if customer not yet selected.
     *  - Price map structure:
     *      [
     *          ''
     *      ]
     *
     * @api
     *
     * @deprecated Please use resolveProductPriceTransfer() instead.
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
    public function resolveProductPriceTransfer(array $priceProductTransfers);
}
