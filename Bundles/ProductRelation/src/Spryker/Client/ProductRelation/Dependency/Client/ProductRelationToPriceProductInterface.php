<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductRelation\Dependency\Client;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;

interface ProductRelationToPriceProductInterface
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
    public function resolveProductAbstractPriceByPriceDimension(array $defaultPriceMap, int $idProductAbstract): CurrentProductPriceTransfer;
}
