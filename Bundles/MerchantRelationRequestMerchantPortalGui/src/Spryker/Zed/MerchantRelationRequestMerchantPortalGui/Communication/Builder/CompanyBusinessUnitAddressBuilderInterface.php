<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Builder;

use ArrayObject;

interface CompanyBusinessUnitAddressBuilderInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\CompanyBusinessUnitTransfer> $companyBusinessUnitTransfers
     *
     * @return array<int, list<string>>
     */
    public function buildCompanyBusinessUnitAddressesGroupedByIdCompanyBusinessUnit(
        ArrayObject $companyBusinessUnitTransfers
    ): array;
}
