<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Asset\Form\DataProvider;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Spryker\Client\CompanyBusinessUnit\CompanyBusinessUnitClientInterface;
use Spryker\Client\CompanyUser\CompanyUserClientInterface;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewBusinessUnitSspAssetPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanySspAssetPermissionPlugin;
use SprykerFeature\Yves\SelfServicePortal\Asset\Form\SspAssetSearchFiltersForm;

class SspAssetSearchFormDataProvider
{
    use PermissionAwareTrait;

    /**
     * @var string
     */
    protected const SCOPE_FILTER_BY_COMPANY = 'filterByCompany';

    /**
     * @var string
     */
    protected const SCOPE_FILTER_BY_COMPANY_LABEL = 'customer.ssp_asset.filter_by_company';

    public function __construct(
        protected CompanyUserClientInterface $companyUserClient,
        protected CompanyBusinessUnitClientInterface $companyBusinessUnitClient
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        $scopeOptions = $this->getScopeOptions();

        return [
            SspAssetSearchFiltersForm::SCOPE_OPTIONS => $scopeOptions,
            SspAssetSearchFiltersForm::SCOPE_DEFAULT_OPTION => $this->getDefaultScopeValue($scopeOptions),
        ];
    }

    /**
     * @return array<string, int|string>
     */
    protected function getScopeOptions(): array
    {
        $companyUserTransfer = $this->companyUserClient->findCompanyUser();
        if (!$companyUserTransfer?->getIdCompanyUser()) {
            return [];
        }

        $idCompanyUser = $companyUserTransfer->getIdCompanyUserOrFail();
        $scopeOptions = [];

        if ($this->can(ViewCompanySspAssetPermissionPlugin::KEY, $idCompanyUser)) {
            $companyBusinessUnitCollectionTransfer = $this->companyBusinessUnitClient->getCompanyBusinessUnitCollection(
                (new CompanyBusinessUnitCriteriaFilterTransfer())->setIdCompany($companyUserTransfer->getCompanyOrFail()->getIdCompanyOrFail()),
            );

            foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
                $scopeOptions[$companyBusinessUnitTransfer->getNameOrFail()] = $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail();
            }
        }

        if ($this->can(ViewBusinessUnitSspAssetPermissionPlugin::KEY, $idCompanyUser)) {
            $companyBusinessUnitTransfer = $companyUserTransfer->getCompanyBusinessUnitOrFail();
            $scopeOptions[$companyBusinessUnitTransfer->getNameOrFail()] = $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail();
        }

        $scopeOptions[static::SCOPE_FILTER_BY_COMPANY_LABEL] = static::SCOPE_FILTER_BY_COMPANY;

        return $scopeOptions;
    }

    /**
     * @param array<string, int|string> $scopeOptions
     *
     * @return string|int|null
     */
    protected function getDefaultScopeValue(array $scopeOptions): int|string|null
    {
        $companyUserTransfer = $this->companyUserClient->findCompanyUser();

        if (!$companyUserTransfer || !$companyUserTransfer->getCompanyBusinessUnit()) {
            return static::SCOPE_FILTER_BY_COMPANY;
        }

        $activeBusinessUnitId = $companyUserTransfer->getCompanyBusinessUnit()->getIdCompanyBusinessUnit();

        if (!$activeBusinessUnitId || !in_array($activeBusinessUnitId, $scopeOptions, true)) {
            return static::SCOPE_FILTER_BY_COMPANY;
        }

        return $activeBusinessUnitId;
    }
}
