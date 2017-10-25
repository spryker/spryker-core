<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Dependency\Facade;

interface ProductRelationToPriceProductInterface
{
    /**
     * @param string $sku
     *
     * @return int
     */
    public function getPriceBySku($sku);
}
