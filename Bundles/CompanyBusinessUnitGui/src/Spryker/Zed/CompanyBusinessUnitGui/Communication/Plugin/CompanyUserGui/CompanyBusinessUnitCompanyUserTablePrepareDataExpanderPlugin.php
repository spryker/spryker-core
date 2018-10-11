<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\Plugin\CompanyUserGui;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Spryker\Zed\CompanyUserGui\CompanyUserGuiConfig;
use Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserTablePrepareDataExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitGui\Communication\CompanyBusinessUnitGuiCommunicationFactory getFactory()
 */
class CompanyBusinessUnitCompanyUserTablePrepareDataExpanderPlugin extends AbstractPlugin implements CompanyUserTablePrepareDataExpanderPluginInterface
{
    /**
     * @see \Spryker\Zed\CompanyUserGui\CompanyUserGuiConfig
     */
    protected const COL_ID_COMPANY_USER = CompanyUserGuiConfig::COL_ID_COMPANY_USER;

    /**
     * {@inheritdoc}
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
        $idCompanyUser = $companyUserDataItem[static::COL_ID_COMPANY_USER];

        $companyBusinessUnits = $this->getFactory()
            ->getCompanyBusinessUnitFacade()
            ->getCompanyBusinessUnitCollection(
                (new CompanyBusinessUnitCriteriaFilterTransfer())->setIdCompanyUser($idCompanyUser)
            )
            ->getCompanyBusinessUnits();

        $companyBusinessUnitName = null;
        if ($companyBusinessUnits->count() > 0) {
            $companyBusinessUnitName = $companyBusinessUnits->offsetGet(0)->getName();
        }

        return array_merge(
            $companyUserDataItem,
            [
                CompanyBusinessUnitCompanyUserTableConfigExpanderPlugin::COL_COMPANY_BUSINESS_UNIT_NAME => $companyBusinessUnitName,
            ]
        );
    }
}
