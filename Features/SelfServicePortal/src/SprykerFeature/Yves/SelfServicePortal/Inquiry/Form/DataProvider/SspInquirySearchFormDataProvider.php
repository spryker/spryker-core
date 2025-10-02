<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Inquiry\Form\DataProvider;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Spryker\Client\CompanyBusinessUnit\CompanyBusinessUnitClientInterface;
use Spryker\Client\CompanyUser\CompanyUserClientInterface;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewBusinessUnitSspInquiryPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanySspInquiryPermissionPlugin;
use SprykerFeature\Yves\SelfServicePortal\Inquiry\Form\SspInquirySearchFiltersForm;
use SprykerFeature\Yves\SelfServicePortal\Inquiry\Form\SspInquirySearchForm;
use SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig;

class SspInquirySearchFormDataProvider
{
    use PermissionAwareTrait;

    /**
     * @var string
     */
    protected const ACCESS_LEVEL_MY_INQUIRIES = 'myInquiries';

    /**
     * @var string
     */
    protected const ACCESS_LEVEL_COMPANY_INQUIRIES = 'companyInquiries';

    /**
     * @var string
     */
    protected const ACCESS_LEVEL_MY_INQUIRIES_LABEL = 'customer.ssp_inquiries.access_level.my_inquiries';

    /**
     * @var string
     */
    protected const ACCESS_LEVEL_COMPANY_INQUIRIES_LABEL = 'customer.ssp_inquiries.access_level.company_inquiries';

    public function __construct(
        protected SelfServicePortalConfig $selfServicePortalConfig,
        protected ?string $currentTimezone,
        protected CompanyUserClientInterface $companyUserClient,
        protected CompanyBusinessUnitClientInterface $companyBusinessUnitClient
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        $allSelectableSspInquiryTypes = array_merge(...array_values($this->selfServicePortalConfig->getSelectableSspInquiryTypes()));
        $mappedTypes = array_combine(array_map(fn ($type) => 'self_service_portal.inquiry.type.' . $type, $allSelectableSspInquiryTypes), $allSelectableSspInquiryTypes);
        $mappedStatuses = array_combine(array_map(fn ($status) => 'self_service_portal.inquiry.status.' . $status, $this->selfServicePortalConfig->getSspInquiryAvailableStatuses()), $this->selfServicePortalConfig->getSspInquiryAvailableStatuses());
        $accessLevels = $this->getAccessLevelOptions();

        return [
            SspInquirySearchForm::OPTION_SSP_INQUIRY_TYPES => $mappedTypes,
            SspInquirySearchForm::OPTION_SSP_INQUIRY_STATUSES => $mappedStatuses,
            SspInquirySearchForm::OPTION_CURRENT_TIMEZONE => $this->currentTimezone,
            SspInquirySearchFiltersForm::OPTION_ACCESS_LEVELS => $accessLevels,
            SspInquirySearchFiltersForm::OPTION_DEFAULT_ACCESS_LEVEL => $this->getDefaultAccessLevelValue($accessLevels),
        ];
    }

    /**
     * @return array<string, int|string>
     */
    protected function getAccessLevelOptions(): array
    {
        $companyUserTransfer = $this->companyUserClient->findCompanyUser();
        if (!$companyUserTransfer?->getIdCompanyUser()) {
            return [];
        }

        $idCompanyUser = $companyUserTransfer->getIdCompanyUserOrFail();
        $accessLevels = [
            static::ACCESS_LEVEL_MY_INQUIRIES_LABEL => static::ACCESS_LEVEL_MY_INQUIRIES,
        ];

        if ($this->can(ViewCompanySspInquiryPermissionPlugin::KEY, $idCompanyUser)) {
            $companyBusinessUnitCollectionTransfer = $this->companyBusinessUnitClient->getCompanyBusinessUnitCollection(
                (new CompanyBusinessUnitCriteriaFilterTransfer())->setIdCompany($companyUserTransfer->getCompanyOrFail()->getIdCompanyOrFail()),
            );

            foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
                $accessLevels[$companyBusinessUnitTransfer->getNameOrFail()] = $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail();
            }

            $accessLevels[static::ACCESS_LEVEL_COMPANY_INQUIRIES_LABEL] = static::ACCESS_LEVEL_COMPANY_INQUIRIES;
        }

        if ($this->can(ViewBusinessUnitSspInquiryPermissionPlugin::KEY, $idCompanyUser)) {
            $companyBusinessUnitTransfer = $companyUserTransfer->getCompanyBusinessUnitOrFail();
            $accessLevels[$companyBusinessUnitTransfer->getNameOrFail()] = $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail();
        }

        return $accessLevels;
    }

    /**
     * @param array<string, int|string> $accessLevels
     *
     * @return string|int|null
     */
    protected function getDefaultAccessLevelValue(array $accessLevels): int|string|null
    {
        $companyUserTransfer = $this->companyUserClient->findCompanyUser();

        if (!$companyUserTransfer || !$companyUserTransfer->getCompanyBusinessUnit()) {
            return static::ACCESS_LEVEL_MY_INQUIRIES;
        }

        $activeBusinessUnitId = $companyUserTransfer->getCompanyBusinessUnit()->getIdCompanyBusinessUnit();

        if (!$activeBusinessUnitId || !in_array($activeBusinessUnitId, $accessLevels, true)) {
            return static::ACCESS_LEVEL_MY_INQUIRIES;
        }

        return $activeBusinessUnitId;
    }
}
