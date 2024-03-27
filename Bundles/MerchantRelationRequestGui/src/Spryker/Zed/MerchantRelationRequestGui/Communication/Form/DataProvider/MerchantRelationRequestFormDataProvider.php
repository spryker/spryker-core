<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Zed\MerchantRelationRequestGui\Communication\Form\MerchantRelationRequestForm;
use Spryker\Zed\MerchantRelationRequestGui\Dependency\Facade\MerchantRelationRequestGuiToCompanyBusinessUnitFacadeInterface;

class MerchantRelationRequestFormDataProvider implements MerchantRelationRequestFormDataProviderInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationRequestGui\Dependency\Facade\MerchantRelationRequestGuiToCompanyBusinessUnitFacadeInterface
     */
    protected MerchantRelationRequestGuiToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationRequestGui\Dependency\Facade\MerchantRelationRequestGuiToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     */
    public function __construct(MerchantRelationRequestGuiToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade)
    {
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
    }

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
        $idCompany = $merchantRelationRequestTransfer
            ->getOwnerCompanyBusinessUnitOrFail()
            ->getCompanyOrFail()
            ->getIdCompanyOrFail();
        $companyBusinessUnitCriteriaFilterTransfer = (new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setIdCompany($idCompany)
            ->setWithoutExpanders(true);

        $companyBusinessUnitCollectionTransfer = $this->companyBusinessUnitFacade
            ->getCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer);

        $assigneeCompanyBusinessUnitsChoices = [];

        foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $assigneeCompanyBusinessUnitsChoices[$companyBusinessUnitTransfer->getNameOrFail()] = $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail();
        }

        return $assigneeCompanyBusinessUnitsChoices;
    }
}
