<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\Generator;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;

class CompanyBusinessUnitNameGenerator implements CompanyBusinessUnitNameGeneratorInterface
{
    protected const NAME_PATTERN = '%s (id: %s)';

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return string
     */
    public function generateName(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): string
    {
        return sprintf(
            static::NAME_PATTERN,
            $companyBusinessUnitTransfer->getName(),
            $companyBusinessUnitTransfer->getIdCompanyBusinessUnit()
        );
    }
}
