<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantity\Persistence;

interface ProductQuantityRepositoryInterface
{
    /**
     * @api
     *
     * @param string[] $productSkus
     *
     * @return \Generated\Shared\Transfer\SpyProductQuantityEntityTransfer[]
     */
    public function findProductQuantityEntitiesByProductSku(array $productSkus): array;

    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SpyProductQuantityEntityTransfer[]
     */
    public function findProductQuantityEntitiesByProductIds(array $productIds): array;
}
