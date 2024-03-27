<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Grouper;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;

interface CompanyBusinessUnitAddressGrouperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer
     *
     * @return array<int, list<string>>
     */
    public function getCompanyBusinessUnitAddressesGroupedByIdCompanyBusinessUnit(
        CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer
    ): array;
}
