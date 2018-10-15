<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\Formatter;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;

class CompanyBusinessUnitGuiFormatter implements CompanyBusinessUnitGuiFormatterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollection
     *
     * @return string|null
     */
    public function getCompanyBusinessUnitName(CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollection): ?string
    {
        $companyBusinessUnitName = null;
        $companyBusinessUnits = $companyBusinessUnitCollection->getCompanyBusinessUnits();

        if ($companyBusinessUnits->count() > 0) {
            $companyBusinessUnitName = $companyBusinessUnits->offsetGet(0)->getName();
        }

        return $companyBusinessUnitName;
    }
}
