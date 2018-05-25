<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\CompanyBusinessUnitForm;
use Spryker\Zed\CompanyBusinessUnitGui\Dependency\Facade\CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface;
use Spryker\Zed\CompanyBusinessUnitGui\Dependency\Facade\CompanyBusinessUnitGuiToCompanyFacadeInterface;

class CompanyBusinessUnitFormDataProvider
{
    protected const OPTION_ATTRIBUTE_DATA = 'data-id_company';

    /**
     * @var \Spryker\Zed\CompanyBusinessUnitGui\Dependency\Facade\CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface
     */
    protected $companyBusinessUnitFacade;

    /**
     * @var \Spryker\Zed\CompanyBusinessUnitGui\Dependency\Facade\CompanyBusinessUnitGuiToCompanyFacadeInterface
     */
    protected $companyFacade;

    /**
     * @param \Spryker\Zed\CompanyBusinessUnitGui\Dependency\Facade\CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     * @param \Spryker\Zed\CompanyBusinessUnitGui\Dependency\Facade\CompanyBusinessUnitGuiToCompanyFacadeInterface $companyFacade
     */
    public function __construct(
        CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade,
        CompanyBusinessUnitGuiToCompanyFacadeInterface $companyFacade
    ) {
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
        $this->companyFacade = $companyFacade;
    }

    /**
     * @param int|null $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function getData(?int $idCompanyBusinessUnit = null): CompanyBusinessUnitTransfer
    {
        $companyBusinessUnitTransfer = $this->createCompanyBusinessUnitTransfer();
        if (!$idCompanyBusinessUnit) {
            return $companyBusinessUnitTransfer;
        }

        return $this->companyBusinessUnitFacade->getCompanyBusinessUnitById(
            $companyBusinessUnitTransfer->setIdCompanyBusinessUnit($idCompanyBusinessUnit)
        );
    }

    /**
     * @param int|null $idCompanyBusinessUnit
     *
     * @return array
     */
    public function getOptions(?int $idCompanyBusinessUnit = null): array
    {
        list($choicesValues, $choicesAttributes) = $this->prepareUnitParentAttributeMap();

        return [
            'data_class' => CompanyBusinessUnitTransfer::class,
            CompanyBusinessUnitForm::OPTION_COMPANY_CHOICES => $this->prepareCompanyChoices(),
            CompanyBusinessUnitForm::OPTION_PARENT_CHOICES_VALUES => $choicesValues,
            CompanyBusinessUnitForm::OPTION_PARENT_CHOICES_ATTRIBUTES => $choicesAttributes,
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    protected function createCompanyBusinessUnitTransfer(): CompanyBusinessUnitTransfer
    {
        return new CompanyBusinessUnitTransfer();
    }

    /**
     * @return int[] [company name => company id]
     */
    protected function prepareCompanyChoices(): array
    {
        $result = [];

        foreach ($this->companyFacade->getCompanies()->getCompanies() as $company) {
            $result[$company->getName()] = $company->getIdCompany();
        }

        return $result;
    }

    /**
     * @return array [[unitKey => idUnit], [unitKey => ['data-id_company' => idCompany]]]
     *                Where unitKey: "<idUnit> - <unitName>"
     */
    protected function prepareUnitParentAttributeMap(): array
    {
        $businessUnitCollection = $this->companyBusinessUnitFacade
            ->getCompanyBusinessUnitCollection(new CompanyBusinessUnitCriteriaFilterTransfer())
            ->getCompanyBusinessUnits();
        $values = [];
        $attributes = [];

        foreach ($businessUnitCollection as $businessUnit) {
            $unitKey = sprintf('%s - %s', $businessUnit->getIdCompanyBusinessUnit(), $businessUnit->getName());
            $values[$unitKey] = $businessUnit->getIdCompanyBusinessUnit();
            $attributes[$unitKey] = [static::OPTION_ATTRIBUTE_DATA => $businessUnit->getFkCompany()];
        }

        return [$values, $attributes];
    }
}
