<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspServiceManagement\Handler;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Generated\Shared\Transfer\SspServiceConditionsTransfer;
use Generated\Shared\Transfer\SspServiceCriteriaTransfer;
use Generated\Shared\Transfer\SspServicesSearchConditionGroupTransfer;
use Spryker\Client\Customer\CustomerClientInterface;
use SprykerFeature\Yves\SspServiceManagement\Form\ServiceSearchForm;
use SprykerFeature\Yves\SspServiceManagement\SspServiceManagementConstants;
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
     * @var string
     */
    protected const PARAMETER_PAGE = 'page';

    /**
     * @var string
     */
    protected const PARAMETER_ITEMS_PER_PAGE = 'ipp';

    /**
     * @var int
     */
    protected const DEFAULT_PAGE = 1;

    /**
     * @var \Spryker\Client\Customer\CustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \Spryker\Client\Customer\CustomerClientInterface $customerClient
     */
    public function __construct(CustomerClientInterface $customerClient)
    {
        $this->customerClient = $customerClient;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $serviceSearchForm
     * @param \Generated\Shared\Transfer\SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
     * @param string|null $productServiceTypeName
     *
     * @return \Generated\Shared\Transfer\SspServiceCriteriaTransfer
     */
    public function handleServiceSearchFormSubmit(
        FormInterface $serviceSearchForm,
        SspServiceCriteriaTransfer $sspServiceCriteriaTransfer,
        ?string $productServiceTypeName = null
    ): SspServiceCriteriaTransfer {
        $sspServiceCriteriaTransfer = $this->addProductTypeFilter($sspServiceCriteriaTransfer, $productServiceTypeName);

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

    /**
     * @param \Generated\Shared\Transfer\SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
     * @param string|null $productType
     *
     * @return \Generated\Shared\Transfer\SspServiceCriteriaTransfer
     */
    protected function addProductTypeFilter(
        SspServiceCriteriaTransfer $sspServiceCriteriaTransfer,
        ?string $productType = null
    ): SspServiceCriteriaTransfer {
        if (!$productType) {
            return $sspServiceCriteriaTransfer;
        }

        $sspServiceConditionsTransfer = $sspServiceCriteriaTransfer->getServiceConditions();
        if (!$sspServiceConditionsTransfer) {
            $sspServiceConditionsTransfer = new SspServiceConditionsTransfer();
            $sspServiceCriteriaTransfer->setServiceConditions($sspServiceConditionsTransfer);
        }

        $sspServiceConditionsTransfer->setProductType($productType);

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

        $sspServicesSearchConditionGroup = new SspServicesSearchConditionGroupTransfer();
        $trimmedSearchText = trim($searchText);

        switch ($searchType) {
            case SspServiceManagementConstants::SEARCH_TYPE_SERVICE_NAME:
                $sspServicesSearchConditionGroup->setProductName($trimmedSearchText);

                break;
            case SspServiceManagementConstants::SEARCH_TYPE_SERVICE_SKU:
                $sspServicesSearchConditionGroup->setSku($trimmedSearchText);

                break;
            case SspServiceManagementConstants::SEARCH_TYPE_ORDER_REFERENCE:
                $sspServicesSearchConditionGroup->setOrderReference($trimmedSearchText);

                break;
        }

        $sspServiceConditionsTransfer = new SspServiceConditionsTransfer();
        $sspServiceConditionsTransfer->setServicesSearchConditionGroup($sspServicesSearchConditionGroup);

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

    /**
     * @param \Generated\Shared\Transfer\SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspServiceCriteriaTransfer
     */
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

    /**
     * @param \Generated\Shared\Transfer\SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspServiceCriteriaTransfer
     */
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

    /**
     * @param \Generated\Shared\Transfer\SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
     * @param string $orderBy
     * @param string|null $orderDirection
     *
     * @return \Generated\Shared\Transfer\SspServiceCriteriaTransfer
     */
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

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return string|null
     */
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
