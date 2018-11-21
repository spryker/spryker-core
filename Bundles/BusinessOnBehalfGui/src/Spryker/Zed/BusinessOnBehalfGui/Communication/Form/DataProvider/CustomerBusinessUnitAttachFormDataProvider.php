<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\BusinessOnBehalfGui\Communication\Form\CustomerBusinessUnitAttachForm;
use Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyBusinessUnitFacadeInterface;
use Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyUserFacadeInterface;

class CustomerBusinessUnitAttachFormDataProvider
{
    protected const OPTION_ATTRIBUTE_DATA = 'data-id_company';
    protected const OPTION_IS_DEFAULT = 'data-is_default';

    protected const FORMAT_NAME = '%s (id: %s)';

    /**
     * @var \Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyBusinessUnitFacadeInterface
     */
    protected $companyBusinessUnitFacade;

    /**
     * @var \Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyUserFacadeInterface
     */
    protected $companyUserFacade;

    /**
     * @param \Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     * @param \Spryker\Zed\BusinessOnBehalfGui\Dependency\Facade\BusinessOnBehalfGuiToCompanyUserFacadeInterface $companyUserFacade
     */
    public function __construct(
        BusinessOnBehalfGuiToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade,
        BusinessOnBehalfGuiToCompanyUserFacadeInterface $companyUserFacade
    ) {
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
        $this->companyUserFacade = $companyUserFacade;
    }

    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function getData(int $idCompanyUser): CompanyUserTransfer
    {
        $companyUserTransfer = $this->companyUserFacade->getCompanyUserById($idCompanyUser);

        return (new CompanyUserTransfer())
            ->setCustomer($companyUserTransfer->getCustomer())
            ->setCompany($companyUserTransfer->getCompany())
            ->setFkCompany($companyUserTransfer->getCompany()->getIdCompany())
            ->setFkCustomer($companyUserTransfer->getCustomer()->getIdCustomer());
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return array
     */
    public function getOptions(CompanyUserTransfer $companyUserTransfer): array
    {
        [$companyBusinessUnitChoicesValues, $companyBusinessUnitChoicesAttributes] = $this->prepareCompanyBusinessUnitAttributeMap($companyUserTransfer);

        return [
            CustomerBusinessUnitAttachForm::OPTION_VALUES_BUSINESS_UNITS_CHOICES => $companyBusinessUnitChoicesValues,
            CustomerBusinessUnitAttachForm::OPTION_ATTRIBUTES_BUSINESS_UNITS_CHOICES => $companyBusinessUnitChoicesAttributes,
            'data_class' => CompanyUserTransfer::class,
        ];
    }

    /**
     * Retrieves the list of units for the same company.
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return array [[unitKey => idBusinessUnit], [unitKey => ['data-id-company' => idCompany]]]
     *                Where unitKey: "<idBusinessUnit> - <BusinessUnitName>"
     */
    protected function prepareCompanyBusinessUnitAttributeMap(CompanyUserTransfer $companyUserTransfer): array
    {
        $values = [];
        $attributes = [];

        $companyBusinessUnitCollection = $this->companyBusinessUnitFacade->getCompanyBusinessUnitCollection(
            (new CompanyBusinessUnitCriteriaFilterTransfer())->setIdCompany($companyUserTransfer->getCompany()->getIdCompany())
        );

        foreach ($companyBusinessUnitCollection->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $unitKey = $this->generateCompanyBusinessUnitName($companyBusinessUnitTransfer);

            $values[$unitKey] = $companyBusinessUnitTransfer->getIdCompanyBusinessUnit();
            $attributes[$unitKey] = [static::OPTION_ATTRIBUTE_DATA => $companyBusinessUnitTransfer->getFkCompany()];
        }

        return [$values, $attributes];
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return string
     */
    protected function generateCompanyBusinessUnitName(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): string
    {
        return sprintf(static::FORMAT_NAME, $companyBusinessUnitTransfer->getName(), $companyBusinessUnitTransfer->getIdCompanyBusinessUnit());
    }
}
