<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Form\MerchantRelationRequestForm;

class MerchantRelationRequestFormDataProvider implements MerchantRelationRequestFormDataProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return array<string, array<string, int>>
     */
    public function getOptions(MerchantRelationRequestTransfer $merchantRelationRequestTransfer): array
    {
        return [
            MerchantRelationRequestForm::OPTION_ASSIGNEE_BUSINESS_UNITS_CHOICES => $this->getAssigneeCompanyBusinessUnitsChoices($merchantRelationRequestTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return array<string, int>
     */
    protected function getAssigneeCompanyBusinessUnitsChoices(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer
    ): array {
        $assigneeCompanyBusinessUnitsChoices = [];

        foreach ($merchantRelationRequestTransfer->getAssigneeCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $assigneeCompanyBusinessUnitsChoices[$companyBusinessUnitTransfer->getNameOrFail()] = $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail();
        }

        return $assigneeCompanyBusinessUnitsChoices;
    }
}
