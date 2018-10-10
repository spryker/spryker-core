<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Persistence;

interface ProductPageSearchRepositoryInterface
{
    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    public function getProductAbstractLocalizedEntitiesByIds(array $productAbstractIds): array;
}
