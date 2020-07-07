<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductGui\Persistence;

use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\QueryCriteriaTransfer;

interface MerchantProductGuiRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function expandQueryCriteriaTransferWithMerchantProductRelation(
        QueryCriteriaTransfer $queryCriteriaTransfer,
        MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
    ): QueryCriteriaTransfer;
}
