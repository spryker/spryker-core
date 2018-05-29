<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\MerchantRelationshipGui\Dependency\Facade\MerchantRelationshipGuiToCompanyBusinessUnitFacadeInterface;
use Spryker\Zed\MerchantRelationshipGui\Dependency\Facade\MerchantRelationshipGuiToCompanyFacadeInterface;
use Spryker\Zed\MerchantRelationshipGui\Dependency\Facade\MerchantRelationshipGuiToMerchantFacadeInterface;
use Spryker\Zed\MerchantRelationshipGui\Dependency\Facade\MerchantRelationshipGuiToMerchantRelationshipFacadeInterface;

class MerchantRelationshipFormDataProvider
{
    public const OPTION_SELECTED_COMPANY = 'idCompany';
    public const OPTION_COMPANY_CHOICES = 'company_choices';
    public const OPTION_MERCHANT_CHOICES = 'merchant_choices';
    public const OPTION_ASSIGNED_COMPANY_BUSINESS_UNIT_CHOICES = 'assignee_company_business_unit_choices';

    /**
     * @var \Spryker\Zed\MerchantRelationshipGui\Dependency\Facade\MerchantRelationshipGuiToMerchantRelationshipFacadeInterface
     */
    protected $merchantRelationshipFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationshipGui\Dependency\Facade\MerchantRelationshipGuiToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationshipGui\Dependency\Facade\MerchantRelationshipGuiToCompanyBusinessUnitFacadeInterface
     */
    protected $companyBusinessUnitFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationshipGui\Dependency\Facade\MerchantRelationshipGuiToCompanyFacadeInterface
     */
    protected $companyFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationshipGui\Dependency\Facade\MerchantRelationshipGuiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade
     * @param \Spryker\Zed\MerchantRelationshipGui\Dependency\Facade\MerchantRelationshipGuiToMerchantFacadeInterface $merchantFacade
     * @param \Spryker\Zed\MerchantRelationshipGui\Dependency\Facade\MerchantRelationshipGuiToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     * @param \Spryker\Zed\MerchantRelationshipGui\Dependency\Facade\MerchantRelationshipGuiToCompanyFacadeInterface $companyFacade
     */
    public function __construct(
        MerchantRelationshipGuiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade,
        MerchantRelationshipGuiToMerchantFacadeInterface $merchantFacade,
        MerchantRelationshipGuiToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade,
        MerchantRelationshipGuiToCompanyFacadeInterface $companyFacade
    ) {
        $this->merchantRelationshipFacade = $merchantRelationshipFacade;
        $this->merchantFacade = $merchantFacade;
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
        $this->companyFacade = $companyFacade;
    }

    /**
     * @param int|null $idMerchantRelationship
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function getData(?int $idMerchantRelationship = null): MerchantRelationshipTransfer
    {
        $merchantRelationship = $this->createMerchantRelationshipTransfer();
        if (!$idMerchantRelationship) {
            return $merchantRelationship;
        }

        $merchantRelationship->setIdMerchantRelationship($idMerchantRelationship);

        return $this->merchantRelationshipFacade->getMerchantRelationshipById($merchantRelationship);
    }

    /**
     * @param int|null $idCompany
     *
     * @return array
     */
    public function getOptions(?int $idCompany = null): array
    {
        return [
            'data_class' => MerchantRelationshipTransfer::class,
            static::OPTION_ASSIGNED_COMPANY_BUSINESS_UNIT_CHOICES => $this->getAssigneeCompanyBusinessUnitChoices($idCompany),
            static::OPTION_COMPANY_CHOICES => $this->getCompanyChoices(),
            static::OPTION_MERCHANT_CHOICES => $this->getMerchantChoices(),
            static::OPTION_SELECTED_COMPANY => $idCompany,
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    protected function createMerchantRelationshipTransfer(): MerchantRelationshipTransfer
    {
        return new MerchantRelationshipTransfer();
    }

    /**
     * @param int|null $idCompany
     *
     * @return array
     */
    protected function getAssigneeCompanyBusinessUnitChoices(?int $idCompany = null): array
    {
        $choices = [];
        if ($idCompany) {
            $companyBusinessUnitCriteriaFilterTransfer = new CompanyBusinessUnitCriteriaFilterTransfer();
            $companyBusinessUnitCriteriaFilterTransfer->setIdCompany($idCompany);

            $companyBusinessUnits = $this->companyBusinessUnitFacade
                ->getCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer)
                ->getCompanyBusinessUnits();

            foreach ($companyBusinessUnits as $companyBusinessUnit) {
                $choices[$companyBusinessUnit->getIdCompanyBusinessUnit()] = $companyBusinessUnit->getName();
            }
        }
        return $choices;
    }

    /**
     * @return array
     */
    protected function getCompanyChoices(): array
    {
        $choices = [];

        foreach ($this->companyFacade->getCompanies()->getCompanies() as $company) {
            $choices[$company->getIdCompany()] = $company->getName();
        }

        return $choices;
    }

    /**
     * @return array
     */
    protected function getMerchantChoices(): array
    {
        $choices = [];

        foreach ($this->merchantFacade->getMerchants()->getMerchants() as $merchant) {
            $choices[$merchant->getIdMerchant()] = $merchant->getName();
        }

        return $choices;
    }
}
