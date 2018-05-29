<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Dependency\Client;

interface PriceProductStorageToPriceProductInterface
{
    /**
     * @param array $priceMap
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function resolveProductPrice(array $priceMap);

    /**
     * @param array $defaultPriceMap
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function resolveProductAbstractPriceByPriceDimension(array $defaultPriceMap, int $idProductAbstract);

    /**
     * @param array $defaultPriceMap
     * @param int $idProductAbstract
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function resolveProductConcretePriceByPriceDimension(array $defaultPriceMap, int $idProductAbstract, int $idProductConcrete);
}
