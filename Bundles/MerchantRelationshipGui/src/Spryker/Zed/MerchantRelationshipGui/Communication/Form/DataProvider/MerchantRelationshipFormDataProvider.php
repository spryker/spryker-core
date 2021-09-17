<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\MerchantRelationshipGui\Dependency\Facade\MerchantRelationshipGuiToCompanyBusinessUnitFacadeInterface;
use Spryker\Zed\MerchantRelationshipGui\Dependency\Facade\MerchantRelationshipGuiToCompanyFacadeInterface;
use Spryker\Zed\MerchantRelationshipGui\Dependency\Facade\MerchantRelationshipGuiToMerchantFacadeInterface;
use Spryker\Zed\MerchantRelationshipGui\Dependency\Facade\MerchantRelationshipGuiToMerchantRelationshipFacadeInterface;

class MerchantRelationshipFormDataProvider
{
    /**
     * @var string
     */
    public const OPTION_SELECTED_COMPANY = 'id_company';
    /**
     * @var string
     */
    public const OPTION_IS_PERSISTENCE_FORM = 'is_persistence_form';
    /**
     * @var string
     */
    public const OPTION_COMPANY_CHOICES = 'company_choices';
    /**
     * @var string
     */
    public const OPTION_MERCHANT_CHOICES = 'merchant_choices';
    /**
     * @var string
     */
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
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer|null
     */
    public function getData(?int $idMerchantRelationship = null): ?MerchantRelationshipTransfer
    {
        $merchantRelationshipTransfer = new MerchantRelationshipTransfer();
        if (!$idMerchantRelationship) {
            return $merchantRelationshipTransfer;
        }

        $merchantRelationshipTransfer->setIdMerchantRelationship($idMerchantRelationship);

        return $this->merchantRelationshipFacade->findMerchantRelationshipById($merchantRelationshipTransfer);
    }

    /**
     * @param bool $isPersistenceForm
     * @param int|null $idCompany
     *
     * @return array<mixed>
     */
    public function getOptions(bool $isPersistenceForm, ?int $idCompany = null): array
    {
        return [
            'data_class' => MerchantRelationshipTransfer::class,
            static::OPTION_ASSIGNED_COMPANY_BUSINESS_UNIT_CHOICES => $this->getAssigneeCompanyBusinessUnitChoices($idCompany),
            static::OPTION_COMPANY_CHOICES => $this->getCompanyChoices(),
            static::OPTION_MERCHANT_CHOICES => $this->getMerchantChoices(),
            static::OPTION_SELECTED_COMPANY => $idCompany,
            static::OPTION_IS_PERSISTENCE_FORM => $isPersistenceForm,
        ];
    }

    /**
     * @param int|null $idCompany
     *
     * @return array<string>
     */
    protected function getAssigneeCompanyBusinessUnitChoices(?int $idCompany = null): array
    {
        $choices = [];
        if ($idCompany) {
            $companyBusinessUnitCriteriaFilterTransfer = new CompanyBusinessUnitCriteriaFilterTransfer();
            $companyBusinessUnitCriteriaFilterTransfer->setIdCompany($idCompany);

            $companyBusinessUnitTransfers = $this->companyBusinessUnitFacade
                ->getCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer)
                ->getCompanyBusinessUnits();

            foreach ($companyBusinessUnitTransfers as $companyBusinessUnitTransfer) {
                $choices[$companyBusinessUnitTransfer->getIdCompanyBusinessUnit()] = sprintf(
                    '%s (ID: %d)',
                    $companyBusinessUnitTransfer->getName(),
                    $companyBusinessUnitTransfer->getIdCompanyBusinessUnit()
                );
            }
        }

        return $choices;
    }

    /**
     * @return array<string>
     */
    protected function getCompanyChoices(): array
    {
        $choices = [];

        foreach ($this->companyFacade->getCompanies()->getCompanies() as $companyTransfer) {
            $choices[$companyTransfer->getIdCompany()] = sprintf(
                '%s (ID: %d)',
                $companyTransfer->getName(),
                $companyTransfer->getIdCompany()
            );
        }

        return $choices;
    }

    /**
     * @return array<string>
     */
    protected function getMerchantChoices(): array
    {
        $choices = [];

        foreach ($this->merchantFacade->get(new MerchantCriteriaTransfer())->getMerchants() as $merchant) {
            $idMerchant = $merchant->getIdMerchant();
            $choices[$idMerchant] = sprintf(
                '%d - %s',
                $idMerchant,
                $merchant->getName()
            );
        }

        return $choices;
    }
}
