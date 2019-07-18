<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Business\CompanyBusinessUnit;

interface CompanyBusinessUnitGuiReaderInterface
{
    /**
     * @param int $idCompanyUser
     *
     * @return string|null
     */
    public function findCompanyBusinessUnitNameByIdCompanyUser(int $idCompanyUser): ?string;
}
