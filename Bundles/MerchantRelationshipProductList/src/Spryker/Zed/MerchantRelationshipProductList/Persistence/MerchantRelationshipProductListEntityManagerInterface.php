<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductList\Persistence;

use ArrayObject;

interface MerchantRelationshipProductListEntityManagerInterface
{
    /**
     * @param array<int> $productListIds
     * @param int $idMerchantRelationship
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductListTransfer>
     */
    public function assignProductListsToMerchantRelationship(array $productListIds, int $idMerchantRelationship): ArrayObject;

    /**
     * @param int $idProductList
     *
     * @return void
     */
    public function clearProductListMerchantRelationship(int $idProductList): void;
}
