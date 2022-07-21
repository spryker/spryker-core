<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\DataProvider;

use Spryker\Zed\CompanyBusinessUnitGui\Communication\Generator\CompanyBusinessUnitNameGeneratorInterface;
use Spryker\Zed\CompanyBusinessUnitGui\Dependency\Facade\CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface;

class CompanyBusinessUnitToCustomerCompanyAttachFormDataProvider
{
    /**
     * @uses \Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\CompanyBusinessUnitToCustomerCompanyAttachForm::OPTION_COMPANY_BUSINESS_UNIT_CHOICES
     *
     * @var string
     */
    protected const OPTION_COMPANY_BUSINESS_UNIT_CHOICES = 'company_business_unit_choices';

    /**
     * @var \Spryker\Zed\CompanyBusinessUnitGui\Dependency\Facade\CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface
     */
    protected $companyBusinessUnitFacade;

    /**
     * @var \Spryker\Zed\CompanyBusinessUnitGui\Communication\Generator\CompanyBusinessUnitNameGeneratorInterface
     */
    protected $companyBusinessUnitNameGenerator;

    /**
     * @param \Spryker\Zed\CompanyBusinessUnitGui\Dependency\Facade\CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     * @param \Spryker\Zed\CompanyBusinessUnitGui\Communication\Generator\CompanyBusinessUnitNameGeneratorInterface $companyBusinessUnitNameGenerator
     */
    public function __construct(
        CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade,
        CompanyBusinessUnitNameGeneratorInterface $companyBusinessUnitNameGenerator
    ) {
        $this->companyBusinessUnitNameGenerator = $companyBusinessUnitNameGenerator;
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
    }

    /**
     * @param int|null $idCompanyBusinessUnit
     *
     * @return array<string, mixed>
     */
    public function getOptions(?int $idCompanyBusinessUnit = null): array
    {
        return [
            static::OPTION_COMPANY_BUSINESS_UNIT_CHOICES => $this->getCompanyBusinessUnitChoices($idCompanyBusinessUnit),
        ];
    }

    /**
     * @param int|null $idCompanyBusinessUnit
     *
     * @return array<string, int>
     */
    public function getCompanyBusinessUnitChoices(?int $idCompanyBusinessUnit = null): array
    {
        if (!$idCompanyBusinessUnit) {
            return [];
        }

        $companyBusinessUnitTransfer = $this->companyBusinessUnitFacade->findCompanyBusinessUnitById($idCompanyBusinessUnit);

        if (!$companyBusinessUnitTransfer) {
            return [];
        }

        $companyBusinessUnitName = $this->companyBusinessUnitNameGenerator->generateName($companyBusinessUnitTransfer);

        return [$companyBusinessUnitName => $idCompanyBusinessUnit];
    }
}
