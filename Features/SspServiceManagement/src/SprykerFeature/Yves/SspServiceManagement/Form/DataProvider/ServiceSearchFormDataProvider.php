<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspServiceManagement\Form\DataProvider;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Spryker\Client\CompanyBusinessUnit\CompanyBusinessUnitClientInterface;
use Spryker\Client\Customer\CustomerClientInterface;
use Spryker\Client\Permission\PermissionClientInterface;
use SprykerFeature\Yves\SspServiceManagement\Form\ServiceSearchForm;
use SprykerFeature\Yves\SspServiceManagement\SspServiceManagementConstants;

class ServiceSearchFormDataProvider
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CHOICE_COMPANY_ORDERS = 'ssp_service_management.list.company_services';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PRODUCT_NAME = 'ssp_service_management.list.product_name';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_SERVICE_SKU = 'ssp_service_management.list.service_sku';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ORDER_REFERENCE = 'ssp_service_management.list.order_reference';

    /**
     * @uses \Spryker\Client\CompanySalesConnector\Plugin\Permission\SeeCompanyOrdersPermissionPlugin
     *
     * @var string
     */
    protected const PERMISSION_SEE_COMPANY_ORDERS = 'SeeCompanyOrdersPermissionPlugin';

    /**
     * @uses \Spryker\Client\CompanySalesConnector\Plugin\Permission\SeeBusinessUnitOrdersPermissionPlugin
     *
     * @var string
     */
    protected const PERMISSION_SEE_BUSINESS_UNIT_ORDERS = 'SeeBusinessUnitOrdersPermissionPlugin';

    /**
     * @var string
     */
    protected const CHOICE_CUSTOMER = 'customer';

    /**
     * @var string
     */
    protected const CHOICE_COMPANY = 'company';

    /**
     * @var \Spryker\Client\Customer\CustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \Spryker\Client\CompanyBusinessUnit\CompanyBusinessUnitClientInterface
     */
    protected $companyBusinessUnitClient;

    /**
     * @var \Spryker\Client\Permission\PermissionClientInterface
     */
    protected $permissionClient;

    /**
     * @param \Spryker\Client\Customer\CustomerClientInterface $customerClient
     * @param \Spryker\Client\CompanyBusinessUnit\CompanyBusinessUnitClientInterface $companyBusinessUnitClient
     * @param \Spryker\Client\Permission\PermissionClientInterface $permissionClient
     */
    public function __construct(
        CustomerClientInterface $customerClient,
        CompanyBusinessUnitClientInterface $companyBusinessUnitClient,
        PermissionClientInterface $permissionClient
    ) {
        $this->customerClient = $customerClient;
        $this->companyBusinessUnitClient = $companyBusinessUnitClient;
        $this->permissionClient = $permissionClient;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return [
            ServiceSearchForm::OPTION_SERVICE_SEARCH_TYPES => $this->getServiceSearchTypes(),
            ServiceSearchForm::OPTION_COMPANY_BUSINESS_UNIT_CHOICES => $this->getCompanyBusinessUnitChoices(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        return [];
    }

    /**
     * @return array<string, string>
     */
    protected function getServiceSearchTypes(): array
    {
        return [
            SspServiceManagementConstants::SEARCH_TYPE_SERVICE_NAME => static::GLOSSARY_KEY_PRODUCT_NAME,
            SspServiceManagementConstants::SEARCH_TYPE_SERVICE_SKU => static::GLOSSARY_KEY_SERVICE_SKU,
            SspServiceManagementConstants::SEARCH_TYPE_ORDER_REFERENCE => static::GLOSSARY_KEY_ORDER_REFERENCE,
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function getCompanyBusinessUnitChoices(): array
    {
        $customerTransfer = $this->customerClient->getCustomer();

        if (!$customerTransfer || !$customerTransfer->getCompanyUserTransfer()) {
            return [];
        }

        $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();
        $idCompanyUser = $companyUserTransfer->getIdCompanyUserOrFail();

        if ($this->can(static::PERMISSION_SEE_COMPANY_ORDERS, $idCompanyUser)) {
            $companyBusinessUnitCriteriaFilterTransfer = (new CompanyBusinessUnitCriteriaFilterTransfer())
                ->setIdCompany($companyUserTransfer->getFkCompany())
                ->setWithoutExpanders(true);

            return $this->getChoicesFromCompanyBusinessUnitCollection(
                $this->companyBusinessUnitClient->getCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer),
            );
        }

        $companyBusinessUnitCollectionTransfer = new CompanyBusinessUnitCollectionTransfer();

        if ($this->can(static::PERMISSION_SEE_BUSINESS_UNIT_ORDERS, $idCompanyUser)) {
            $companyBusinessUnitCollectionTransfer->addCompanyBusinessUnit(
                $companyUserTransfer->getCompanyBusinessUnitOrFail(),
            );
        }

        return $this->getChoicesFromCompanyBusinessUnitCollection($companyBusinessUnitCollectionTransfer);
    }

    /**
     * @param string $permissionKey
     * @param int $idCompanyUser
     *
     * @return bool
     */
    protected function can(string $permissionKey, int $idCompanyUser): bool
    {
        return $this->permissionClient->can($permissionKey, $idCompanyUser);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer
     *
     * @return array<string, string>
     */
    protected function getChoicesFromCompanyBusinessUnitCollection(
        CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer
    ): array {
        $choices = [];

        foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $choices[$companyBusinessUnitTransfer->getNameOrFail()] = $companyBusinessUnitTransfer->getUuidOrFail();
        }

        if (count($choices) > 1) {
            $choices[static::GLOSSARY_KEY_CHOICE_COMPANY_ORDERS] = static::CHOICE_COMPANY;
        }

        return $choices;
    }
}
