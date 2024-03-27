<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Builder;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;

interface CompanyBusinessUnitAddressBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return string
     */
    public function buildCompanyBusinessUnitAddress(CompanyUnitAddressTransfer $companyUnitAddressTransfer): string;
}
