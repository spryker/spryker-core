<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\CompanyUserBusinessUnitChoiceFormType;
use Spryker\Zed\CompanyBusinessUnitGui\Dependency\Facade\CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface;

class CompanyUserBusinessUnitFormDataProvider
{
    protected const OPTION_ATTRIBUTE_DATA = 'data-id_company';

    /**
     * @var \Spryker\Zed\CompanyBusinessUnitGui\Dependency\Facade\CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface
     */
    protected $companyBusinessUnitFacade;

    /**
     * @param \Spryker\Zed\CompanyBusinessUnitGui\Dependency\Facade\CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     */
    public function __construct(CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade)
    {
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function getData(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer
    {
        return $companyUserTransfer;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        [$choicesValues, $choicesAttributes] = $this->prepareCompanyBusinessUnitAttributeMap();

        return [
            CompanyUserBusinessUnitChoiceFormType::OPTION_VALUES_BUSINESS_UNITS_CHOICES => $choicesValues,
            CompanyUserBusinessUnitChoiceFormType::OPTION_ATTRIBUTES_BUSINESS_UNITS_CHOICES => $choicesAttributes,
        ];
    }

    /**
     * Retrieves the list of units for the same company.
     *
     * @return array [[unitKey => idBusinessUnit], [unitKey => ['data-id-company' => idCompany]]]
     *                Where unitKey: "<idBusinessUnit> - <BusinessUnitName>"
     */
    protected function prepareCompanyBusinessUnitAttributeMap(): array
    {
        $values = [];
        $attributes = [];
        $companyBusinessUnitCollection = $this->companyBusinessUnitFacade->getCompanyBusinessUnitCollection(
            (new CompanyBusinessUnitCriteriaFilterTransfer())
        );

        foreach ($companyBusinessUnitCollection->getCompanyBusinessUnits() as $unitTransfer) {
            $unitKey = sprintf('%s - %s', $unitTransfer->getIdCompanyBusinessUnit(), $unitTransfer->getName());
            $values[$unitKey] = $unitTransfer->getIdCompanyBusinessUnit();
            $attributes[$unitKey] = [static::OPTION_ATTRIBUTE_DATA => $unitTransfer->getFkCompany()];
        }

        return [$values, $attributes];
    }
}
