<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole;

use Spryker\Shared\CompanyRole\CompanyRoleConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CompanyRoleConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getCompanyRoleDefaultName(): string
    {
        return $this->get(CompanyRoleConstants::DEFAULT_COMPANY_ROLE_NAME);
    }
}
