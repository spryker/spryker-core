<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Handler;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Generated\Shared\Transfer\SspServiceConditionsTransfer;
use Generated\Shared\Transfer\SspServiceCriteriaTransfer;
use Generated\Shared\Transfer\SspServicesSearchConditionTransfer;
use Spryker\Client\Customer\CustomerClientInterface;
use SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig;
use SprykerFeature\Yves\SelfServicePortal\Service\Form\DataProvider\ServiceSearchFormDataProvider;
use SprykerFeature\Yves\SelfServicePortal\Service\Form\ServiceSearchForm;
use Symfony\Component\Form\FormInterface;

class ServiceSearchFormHandler implements ServiceSearchFormHandlerInterface
{
    /**
     * @var string
     */
    protected const CHOICE_CUSTOMER = 'customer';

    /**
     * @var string
     */
    protected const CHOICE_COMPANY = 'company';

    /**
     * @var string
     */
    protected const FILTER_FIELD_TYPE_DATE = 'created_at';

    /**
     * @var int
     */
    protected const DEFAULT_PAGE = 1;

    public function __construct(protected CustomerClientInterface $customerClient, protected SelfServicePortalConfig $sspServiceManagementConfig)
    {
    }

    public function handleServiceSearchFormSubmit(
        FormInterface $serviceSearchForm,
        SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
    ): SspServiceCriteriaTransfer {
        $sspServiceCriteriaTransfer = $this->addProductClassFilter($sspServiceCriteriaTransfer, $this->sspServiceManagementConfig->getServiceProductClassName());

        if (!$serviceSearchForm->isSubmitted() || !$serviceSearchForm->isValid()) {
            $sspServiceCriteriaTransfer = $this->addCustomerFilter($sspServiceCriteriaTransfer);

            return $this->addOrderByFilter($sspServiceCriteriaTransfer, static::FILTER_FIELD_TYPE_DATE);
        }

        $serviceSearchFormData = $serviceSearchForm->getData();

        $sspServiceCriteriaTransfer = $this->handleSearchTypeInputs($serviceSearchFormData, $sspServiceCriteriaTransfer);
        $sspServiceCriteriaTransfer = $this->handleBusinessUnitTypeSubmit($serviceSearchFormData, $sspServiceCriteriaTransfer);
        $sspServiceCriteriaTransfer = $this->handleOrderInputs($serviceSearchFormData, $sspServiceCriteriaTransfer);

        return $sspServiceCriteriaTransfer;
    }

    protected function addProductClassFilter(
        SspServiceCriteriaTransfer $sspServiceCriteriaTransfer,
        string $productClass
    ): SspServiceCriteriaTransfer {
        $sspServiceConditionsTransfer = $sspServiceCriteriaTransfer->getServiceConditions();
        if (!$sspServiceConditionsTransfer) {
            $sspServiceConditionsTransfer = new SspServiceConditionsTransfer();
            $sspServiceCriteriaTransfer->setServiceConditions($sspServiceConditionsTransfer);
        }

        $sspServiceConditionsTransfer->setProductClass($productClass);

        return $sspServiceCriteriaTransfer;
    }

    /**
     * @param array<string, mixed> $serviceSearchFormData
     * @param \Generated\Shared\Transfer\SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspServiceCriteriaTransfer
     */
    protected function handleSearchTypeInputs(
        array $serviceSearchFormData,
        SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
    ): SspServiceCriteriaTransfer {
        $searchType = $serviceSearchFormData[ServiceSearchForm::FIELD_SEARCH_TYPE] ?? null;
        $searchText = $serviceSearchFormData[ServiceSearchForm::FIELD_SEARCH_TEXT] ?? null;

        if (!$searchType || !$searchText) {
            return $sspServiceCriteriaTransfer;
        }

        $sspServicesSearchCondition = new SspServicesSearchConditionTransfer();
        $trimmedSearchText = trim($searchText);

        switch ($searchType) {
            case ServiceSearchFormDataProvider::SEARCH_TYPE_SERVICE_NAME:
                $sspServicesSearchCondition->setProductName($trimmedSearchText);

                break;
            case ServiceSearchFormDataProvider::SEARCH_TYPE_SERVICE_SKU:
                $sspServicesSearchCondition->setSku($trimmedSearchText);

                break;
            case ServiceSearchFormDataProvider::SEARCH_TYPE_ORDER_REFERENCE:
                $sspServicesSearchCondition->setOrderReference($trimmedSearchText);

                break;
        }

        $sspServiceConditionsTransfer = new SspServiceConditionsTransfer();
        $sspServiceConditionsTransfer->setServicesSearchCondition($sspServicesSearchCondition);

        return $sspServiceCriteriaTransfer->setServiceConditions($sspServiceConditionsTransfer);
    }

    /**
     * @param array<string, mixed> $serviceSearchFormData
     * @param \Generated\Shared\Transfer\SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspServiceCriteriaTransfer
     */
    protected function handleBusinessUnitTypeSubmit(
        array $serviceSearchFormData,
        SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
    ): SspServiceCriteriaTransfer {
        $companyBusinessUnitValue = $serviceSearchFormData[ServiceSearchForm::FILTER_FIELD_TYPE_COMPANY_BUSINESS_UNIT] ?? null;

        if (!$companyBusinessUnitValue || $companyBusinessUnitValue === static::CHOICE_CUSTOMER) {
            return $this->addCustomerFilter($sspServiceCriteriaTransfer);
        }

        $sspServiceConditionsTransfer = $sspServiceCriteriaTransfer->getServiceConditions();
        if (!$sspServiceConditionsTransfer) {
            $sspServiceConditionsTransfer = new SspServiceConditionsTransfer();
            $sspServiceCriteriaTransfer->setServiceConditions($sspServiceConditionsTransfer);
        }

        if ($companyBusinessUnitValue === static::CHOICE_COMPANY) {
            return $this->addCompanyFilter($sspServiceCriteriaTransfer);
        }

        $sspServiceConditionsTransfer->setCompanyBusinessUnitUuid($companyBusinessUnitValue);

        return $sspServiceCriteriaTransfer;
    }

    protected function addCompanyFilter(SspServiceCriteriaTransfer $sspServiceCriteriaTransfer): SspServiceCriteriaTransfer
    {
        $customerTransfer = $this->customerClient->getCustomer();

        if (!$customerTransfer) {
            return $sspServiceCriteriaTransfer;
        }

        $companyUuid = $this->extractCompanyUuid($customerTransfer);

        if (!$companyUuid) {
            return $sspServiceCriteriaTransfer;
        }

        $sspServiceConditionsTransfer = $sspServiceCriteriaTransfer->getServiceConditions();
        if (!$sspServiceConditionsTransfer) {
            $sspServiceConditionsTransfer = new SspServiceConditionsTransfer();
            $sspServiceCriteriaTransfer->setServiceConditions($sspServiceConditionsTransfer);
        }

        $sspServiceConditionsTransfer->setCompanyUuid($companyUuid);

        return $sspServiceCriteriaTransfer;
    }

    protected function addCustomerFilter(SspServiceCriteriaTransfer $sspServiceCriteriaTransfer): SspServiceCriteriaTransfer
    {
        $customerTransfer = $this->customerClient->getCustomer();

        if (!$customerTransfer) {
            return $sspServiceCriteriaTransfer;
        }

        $sspServiceConditionsTransfer = $sspServiceCriteriaTransfer->getServiceConditions();
        if (!$sspServiceConditionsTransfer) {
            $sspServiceConditionsTransfer = new SspServiceConditionsTransfer();
            $sspServiceCriteriaTransfer->setServiceConditions($sspServiceConditionsTransfer);
        }

        $sspServiceConditionsTransfer->setCustomerReference($customerTransfer->getCustomerReference());

        return $sspServiceCriteriaTransfer;
    }

    /**
     * @param array<string, mixed> $serviceSearchFormData
     * @param \Generated\Shared\Transfer\SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspServiceCriteriaTransfer
     */
    protected function handleOrderInputs(
        array $serviceSearchFormData,
        SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
    ): SspServiceCriteriaTransfer {
        $orderBy = $serviceSearchFormData[ServiceSearchForm::FIELD_ORDER_BY] ?? null;
        $orderDirection = $serviceSearchFormData[ServiceSearchForm::FIELD_ORDER_DIRECTION] ?? null;

        if (!$orderBy) {
            $orderBy = static::FILTER_FIELD_TYPE_DATE;
        }

        return $this->addOrderByFilter($sspServiceCriteriaTransfer, $orderBy, $orderDirection);
    }

    protected function addOrderByFilter(
        SspServiceCriteriaTransfer $sspServiceCriteriaTransfer,
        string $orderBy,
        ?string $orderDirection = null
    ): SspServiceCriteriaTransfer {
        if (!$orderDirection) {
            $orderDirection = 'DESC';
        }

        $sortTransfer = new SortTransfer();
        $sortTransfer->setField($orderBy);
        $sortTransfer->setDirection($orderDirection);

        return $sspServiceCriteriaTransfer->addSort($sortTransfer);
    }

    protected function extractCompanyUuid(CustomerTransfer $customerTransfer): ?string
    {
        $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();

        if (!$companyUserTransfer || !$companyUserTransfer->getCompanyBusinessUnit()) {
            return null;
        }

        $companyBusinessUnitTransfer = $companyUserTransfer->getCompanyBusinessUnit();

        if (!$companyBusinessUnitTransfer->getCompany()) {
            return null;
        }

        return $companyBusinessUnitTransfer->getCompany()->getUuid();
    }
}
