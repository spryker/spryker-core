<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductList\Persistence;

interface MerchantRelationshipProductListEntityManagerInterface
{
    /**
     * @param int[] $productListIds
     * @param int $idMerchantRelationship
     *
     * @return void
     */
    public function assignProductListsToMerchantRelationship(array $productListIds, int $idMerchantRelationship): void;

    /**
     * @param int $idProductList
     *
     * @return void
     */
    public function removeMerchantRelationFromProductList(int $idProductList): void;
}
