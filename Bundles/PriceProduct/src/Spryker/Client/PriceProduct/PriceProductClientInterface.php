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
     *
     * @api
     *
     * @param array $priceMap
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function resolveProductPrice(array $priceMap);

//    /**
//     * Specification:
//     *  - Finds price from product abstract price dimensions,
//     *  - executes all PriceDimensionPluginInterface plugins
//     *  - overwrites default price if any found
//     *
//     * @api
//     *
//     * @param array $defaultPriceMap
//     * @param int $idProductAbstract
//     *
//     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
//     */
//    public function resolveProductAbstractPriceByPriceDimension(array $defaultPriceMap, int $idProductAbstract);
//
//    /**
//     * Specification:
//     *  - Finds price from product abstract price dimensions,
//     *  - executes all PriceDimensionPluginInterface plugins
//     *  - overwrites default price if any found
//     *
//     * @api
//     *
//     * @param array $defaultPriceMap
//     * @param int $idProductAbstract
//     * @param int $idProductConcrete
//     *
//     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
//     */
//    public function resolveProductConcretePriceByPriceDimension(array $defaultPriceMap, int $idProductAbstract, int $idProductConcrete);
}
