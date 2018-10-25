<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Business;

interface CompanyBusinessUnitGuiFacadeInterface
{
    /**
     * Specification:
     * - Returns the business unit name for the given id company user.
     *
     * @api
     *
     * @param int $idCompanyUser
     *
     * @return string|null
     */
    public function findCompanyBusinessUnitName(int $idCompanyUser): ?string;
}
