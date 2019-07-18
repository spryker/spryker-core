<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\CompanyUserBusinessUnitForm;
use Spryker\Zed\CompanyBusinessUnitGui\Dependency\Facade\CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface;

class CompanyUserBusinessUnitFormDataProvider
{
    protected const OPTION_ATTRIBUTE_DATA = 'data-id_company';

    protected const FORMAT_NAME = '%s (id: %s)';

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
     * @return array
     */
    public function getOptions(): array
    {
        [$choicesValues, $choicesAttributes] = $this->prepareCompanyBusinessUnitAttributeMap();

        return [
            CompanyUserBusinessUnitForm::OPTION_VALUES_BUSINESS_UNITS_CHOICES => $choicesValues,
            CompanyUserBusinessUnitForm::OPTION_ATTRIBUTES_BUSINESS_UNITS_CHOICES => $choicesAttributes,
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
