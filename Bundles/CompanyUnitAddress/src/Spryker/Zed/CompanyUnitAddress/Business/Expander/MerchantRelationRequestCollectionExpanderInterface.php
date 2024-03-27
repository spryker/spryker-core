<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Business\Expander;

use Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer;

interface MerchantRelationRequestCollectionExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer
     */
    public function expandMerchantRelationRequestCollectionWithAssigneeCompanyBusinessUnitAddress(
        MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
    ): MerchantRelationRequestCollectionTransfer;
}
