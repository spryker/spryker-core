<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Form\MerchantRelationshipForm;

class MerchantRelationshipFormDataProvider
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return array<array<string>>
     */
    public function getOptions(MerchantRelationshipTransfer $merchantRelationshipTransfer): array
    {
        return [
            MerchantRelationshipForm::OPTION_ASSIGNEE_COMPANY_BUSINESS_UNITS_CHOICES => $this->getAssigneeCompanyBusinessUnitChoices(
                $merchantRelationshipTransfer->getAssigneeCompanyBusinessUnitsOrFail(),
            ),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer
     *
     * @return array<int, string>
     */
    protected function getAssigneeCompanyBusinessUnitChoices(CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer): array
    {
        $companyBusinessUnitChoices = [];
        foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $idCompanyBusinessUnit = $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail();
            $companyBusinessUnitChoices[$idCompanyBusinessUnit] = $companyBusinessUnitTransfer->getNameOrFail();
        }

        return $companyBusinessUnitChoices;
    }
}
