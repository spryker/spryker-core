<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOption\Persistence;

use Generated\Shared\Transfer\MerchantProductOptionGroupCollectionTransfer;
use Generated\Shared\Transfer\MerchantProductOptionGroupCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProductOptionGroupTransfer;

interface MerchantProductOptionRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProductOptionGroupCriteriaTransfer $merchantProductOptionGroupCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductOptionGroupCollectionTransfer
     */
    public function getGroups(
        MerchantProductOptionGroupCriteriaTransfer $merchantProductOptionGroupCriteriaTransfer
    ): MerchantProductOptionGroupCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantProductOptionGroupCriteriaTransfer $merchantProductOptionGroupCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductOptionGroupTransfer|null
     */
    public function findGroup(
        MerchantProductOptionGroupCriteriaTransfer $merchantProductOptionGroupCriteriaTransfer
    ): ?MerchantProductOptionGroupTransfer;

    /**
     * @param array<int> $productOptionGroupIds
     *
     * @return array<int|null>
     */
    public function getProductOptionGroupIdsWithNotApprovedMerchantGroups(array $productOptionGroupIds): array;
}
