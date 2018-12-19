<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionStorage\Persistence;

interface ProductOptionStorageRepositoryInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return array [[fkProductAbstract => [productOptionGroupName => productOptionGroupStatus]]]
     */
    public function getProductOptionGroupStatusesByProductAbstractIds($productAbstractIds): array;
}
