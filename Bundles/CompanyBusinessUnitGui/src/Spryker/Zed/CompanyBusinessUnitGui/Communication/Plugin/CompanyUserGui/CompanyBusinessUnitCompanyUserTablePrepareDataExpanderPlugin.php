<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\Plugin\CompanyUserGui;

use Spryker\Zed\CompanyBusinessUnitGui\CompanyBusinessUnitGuiConfig;
use Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserTablePrepareDataExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitGui\Business\CompanyBusinessUnitGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyBusinessUnitGui\Communication\CompanyBusinessUnitGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CompanyBusinessUnitGui\CompanyBusinessUnitGuiConfig getConfig()
 */
class CompanyBusinessUnitCompanyUserTablePrepareDataExpanderPlugin extends AbstractPlugin implements CompanyUserTablePrepareDataExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Extends table data rows of company user table with company business unit names.
     *
     * @api
     *
     * @param array $companyUserDataItem
     *
     * @return array
     */
    public function expandDataItem(array $companyUserDataItem): array
    {
        $companyBusinessUnitName = $this->getFacade()
            ->findCompanyBusinessUnitName($companyUserDataItem[CompanyBusinessUnitGuiConfig::COL_ID_COMPANY_USER]);

        return array_merge(
            $companyUserDataItem,
            [
                CompanyBusinessUnitCompanyUserTableConfigExpanderPlugin::COL_COMPANY_BUSINESS_UNIT_NAME => $companyBusinessUnitName,
            ]
        );
    }
}
