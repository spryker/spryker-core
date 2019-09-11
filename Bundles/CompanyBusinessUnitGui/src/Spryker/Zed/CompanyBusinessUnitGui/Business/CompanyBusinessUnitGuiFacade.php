<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitGui\Business\CompanyBusinessUnitGuiBusinessFactory getFactory()
 */
class CompanyBusinessUnitGuiFacade extends AbstractFacade implements CompanyBusinessUnitGuiFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCompanyUser
     *
     * @return string|null
     */
    public function findCompanyBusinessUnitName(int $idCompanyUser): ?string
    {
        return $this->getFactory()
            ->createCompanyBusinessUnitGuiReader()
            ->findCompanyBusinessUnitNameByIdCompanyUser($idCompanyUser);
    }
}
