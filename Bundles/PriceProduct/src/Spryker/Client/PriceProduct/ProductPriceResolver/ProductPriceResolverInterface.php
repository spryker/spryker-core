<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProduct\ProductPriceResolver;

interface ProductPriceResolverInterface
{
    /**
     * @param array $priceMap
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function resolve(array $priceMap);

    /**
     * @param array $priceMap
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function resolveProductAbstractPriceByPriceDimension(array $priceMap, int $idProductAbstract);

    /**
     * @param array $priceMap
     * @param int $idProductAbstract
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function resolveProductConcretePriceByPriceDimension(array $priceMap, int $idProductAbstract, int $idProductConcrete);
}
