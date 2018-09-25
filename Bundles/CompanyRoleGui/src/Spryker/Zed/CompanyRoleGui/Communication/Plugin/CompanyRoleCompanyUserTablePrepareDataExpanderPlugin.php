<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Communication\Plugin;

use Orm\Zed\CompanyRole\Persistence\Map\SpyCompanyRoleTableMap;
use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserGui\CompanyUserTablePrepareDataExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyRoleGui\Communication\CompanyRoleGuiCommunicationFactory getFactory()
 */
class CompanyRoleCompanyUserTablePrepareDataExpanderPlugin extends AbstractPlugin implements CompanyUserTablePrepareDataExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     * - This plugin allows you to extend data rows of company user table with company roles.
     *
     * @api
     *
     * @param array $companyUserDataItem
     *
     * @return array
     */
    public function expandDataItem(array $companyUserDataItem): array
    {
        $companyRoleName = $this->getFactory()
            ->getPropelCompanyRoleToCompanyUserQuery()
            ->filterByFkCompanyUser($companyUserDataItem[SpyCompanyUserTableMap::COL_ID_COMPANY_USER])
            ->joinCompanyRole()
            ->select(SpyCompanyRoleTableMap::COL_NAME)
            ->findOne();

        $companyUserDataItem += [
            CompanyRoleCompanyUserTableConfigExpanderPlugin::COL_COMPANY_ROLE_NAME => $companyRoleName,
        ];

        return $companyUserDataItem;
    }
}
