<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit;

use Spryker\Shared\CompanyBusinessUnit\CompanyBusinessUnitConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CompanyBusinessUnitConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getCompanyBusinessUnitDefaultName(): string
    {
        return $this->get(CompanyBusinessUnitConstants::COMPANY_BUSINESS_UNIT_DEFAULT_NAME);
    }
}
