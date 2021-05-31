<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOptionGui\Persistence;

use Generated\Shared\Transfer\MerchantProductOptionGroupCriteriaTransfer;
use Generated\Shared\Transfer\QueryCriteriaTransfer;

interface MerchantProductOptionGuiRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     * @param \Generated\Shared\Transfer\MerchantProductOptionGroupCriteriaTransfer $merchantProductOptionGroupCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function expandQueryCriteriaTransferWithMerchantProductOptionRelation(
        QueryCriteriaTransfer $queryCriteriaTransfer,
        MerchantProductOptionGroupCriteriaTransfer $merchantProductOptionGroupCriteriaTransfer
    ): QueryCriteriaTransfer;
}
