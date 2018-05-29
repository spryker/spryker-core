<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Wishlist\Dependency\Client;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;

interface WishlistToPriceProductClientInterface
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
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function resolveProductConcretePriceByPriceDimension(
        array $defaultPriceMap,
        int $idProductAbstract,
        int $idProductConcrete
    ): CurrentProductPriceTransfer;
}
