<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductList\Persistence;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\ProductListCollectionTransfer;

interface MerchantRelationshipProductListRepositoryInterface
{
    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\ProductListCollectionTransfer
     */
    public function getProductListCollectionByIdCompanyBusinessUnit(int $idCompanyBusinessUnit): ProductListCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListCollectionTransfer
     */
    public function getAvailableProductListsForMerchantRelationship(MerchantRelationshipTransfer $merchantRelationshipTransfer): ProductListCollectionTransfer;

    /**
     * @param int $idMerchantRelationship
     *
     * @return \Generated\Shared\Transfer\ProductListCollectionTransfer
     */
    public function findProductListCollectionByIdMerchantRelationship(int $idMerchantRelationship): ProductListCollectionTransfer;
}
