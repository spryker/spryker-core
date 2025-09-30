<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\CompanyFile\Form\DataProvider;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Spryker\Client\CompanyBusinessUnit\CompanyBusinessUnitClientInterface;
use Spryker\Client\CompanyUser\CompanyUserClientInterface;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewBusinessUnitSspAssetPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanyBusinessUnitFilesPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanyFilesPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanySspAssetPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanyUserFilesPermissionPlugin;
use SprykerFeature\Yves\SelfServicePortal\CompanyFile\Form\FileSearchFilterForm;
use SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig;

class FileSearchFilterFormDataProvider
{
    use PermissionAwareTrait;

    /**
     * @var string
     */
    public const FILE_ATTACHMENT_TYPE_COMPANY = 'company';

    /**
     * @var string
     */
    public const FILE_ATTACHMENT_TYPE_COMPANY_LABEL = 'self_service_portal.company_file.file_attachment_type.company';

    /**
     * @var string
     */
    public const FILE_ATTACHMENT_TYPE_COMPANY_USER = 'company_user';

    /**
     * @var string
     */
    public const FILE_ATTACHMENT_TYPE_COMPANY_USER_LABEL = 'self_service_portal.company_file.file_attachment_type.company_user';

    /**
     * @var string
     */
    public const FILE_ATTACHMENT_TYPE_SSP_ASSET = 'ssp_asset';

    /**
     * @var string
     */
    public const FILE_ATTACHMENT_TYPE_NONE = 'none';

    /**
     * @var string
     */
    public const BUSINESS_FILE_ATTACHMENT_TYPE_NONE_LABEL = 'self_service_portal.company_file.file_attachment_business_type.none';

    /**
     * @var string
     */
    public const SSP_ASSET_FILE_ATTACHMENT_TYPE_NONE_LABEL = 'self_service_portal.company_file.file_attachment_ssp_asset_type.none';

    /**
     * @var string
     */
    public const FILE_ATTACHMENT_TYPE_ALL = 'all';

    /**
     * @var string
     */
    public const FILE_ATTACHMENT_TYPE_ALL_LABEL = 'self_service_portal.company_file.file_attachment_type.all';

    public function __construct(
        protected SelfServicePortalConfig $selfServicePortalConfig,
        protected CompanyUserClientInterface $companyUserClient,
        protected CompanyBusinessUnitClientInterface $companyBusinessUnitClient,
        protected SelfServicePortalClientInterface $selfServicePortalClient
    ) {
    }

    /**
     * @return array<string, array<string, mixed>|string>
     */
    public function getOptions(): array
    {
        $businessEntities = $this->getBusinessEntityChoices();

        return [
            FileSearchFilterForm::OPTION_FILE_TYPES => $this->getFileTypesChoices(),
            FileSearchFilterForm::OPTION_BUSINESS_ENTITIES => $businessEntities,
            FileSearchFilterForm::OPTION_SSP_ASSET_ENTITIES => $this->getSspAssetEntityChoices(),
            FileSearchFilterForm::OPTION_DEFAULT_BUSINESS_ENTITY => $this->getDefaultBusinessEntityValue($businessEntities),
        ];
    }

    /**
     * @param array<string, mixed> $businessEntities
     *
     * @return string
     */
    public function getDefaultBusinessEntityValue(array $businessEntities): string
    {
        $companyUserTransfer = $this->companyUserClient->findCompanyUser();

        if (!$companyUserTransfer || !$companyUserTransfer->getCompanyBusinessUnit()) {
            return static::FILE_ATTACHMENT_TYPE_ALL;
        }

        $activeBusinessUnitUuid = $companyUserTransfer->getCompanyBusinessUnit()->getUuid();

        if (!$activeBusinessUnitUuid || !array_key_exists($activeBusinessUnitUuid, $businessEntities)) {
            return static::FILE_ATTACHMENT_TYPE_ALL;
        }

        return $activeBusinessUnitUuid;
    }

    /**
     * @return array<string, string>
     */
    protected function getFileTypesChoices(): array
    {
        return array_combine(
            $this->selfServicePortalConfig->getCompanyFilesAllowedFileTypes(),
            $this->selfServicePortalConfig->getCompanyFilesAllowedFileTypes(),
        );
    }

    /**
     * @return array<string, mixed>
     */
    protected function getBusinessEntityChoices(): array
    {
        $companyUserTransfer = $this->companyUserClient->findCompanyUser();
        if (!$companyUserTransfer?->getIdCompanyUser()) {
            return [];
        }
        $companyUserId = $companyUserTransfer->getIdCompanyUserOrFail();
        $businessEntityTypes = [
            static::FILE_ATTACHMENT_TYPE_ALL => static::FILE_ATTACHMENT_TYPE_ALL_LABEL,
        ];

        if ($this->can(ViewCompanyUserFilesPermissionPlugin::KEY, $companyUserId)) {
            $businessEntityTypes[static::FILE_ATTACHMENT_TYPE_COMPANY_USER] = static::FILE_ATTACHMENT_TYPE_COMPANY_USER_LABEL;
        }

        if ($this->can(ViewCompanyFilesPermissionPlugin::KEY, $companyUserId)) {
            $businessEntityTypes[static::FILE_ATTACHMENT_TYPE_COMPANY] = static::FILE_ATTACHMENT_TYPE_COMPANY_LABEL;

            $businessEntityTypes[static::FILE_ATTACHMENT_TYPE_COMPANY_USER] = static::FILE_ATTACHMENT_TYPE_COMPANY_USER_LABEL;

            $companyBusinessUnitCollectionTransfer = $this->companyBusinessUnitClient->getCompanyBusinessUnitCollection(
                (new CompanyBusinessUnitCriteriaFilterTransfer())->setIdCompany($companyUserTransfer->getCompanyOrFail()->getIdCompanyOrFail()),
            );

            foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
                $businessEntityTypes[$companyBusinessUnitTransfer->getUuidOrFail()] = $companyBusinessUnitTransfer->getNameOrFail();
            }
        }

        if ($this->can(ViewCompanyBusinessUnitFilesPermissionPlugin::KEY, $companyUserId)) {
            $companyBusinessUnitTransfer = $companyUserTransfer->getCompanyBusinessUnitOrFail();
            $businessEntityTypes[$companyBusinessUnitTransfer->getUuidOrFail()] = $companyBusinessUnitTransfer->getNameOrFail();
        }

        $businessEntityTypes[static::FILE_ATTACHMENT_TYPE_NONE] = static::BUSINESS_FILE_ATTACHMENT_TYPE_NONE_LABEL;

        return $businessEntityTypes;
    }

    /**
     * @return array<string, string>
     */
    protected function getSspAssetEntityChoices(): array
    {
        $companyUserTransfer = $this->companyUserClient->findCompanyUser();
        if (!$companyUserTransfer?->getIdCompanyUser()) {
            return [];
        }
        $companyUserId = $companyUserTransfer->getIdCompanyUserOrFail();
        $businessEntityTypes = [
            static::FILE_ATTACHMENT_TYPE_ALL => static::FILE_ATTACHMENT_TYPE_ALL_LABEL,
        ];

        if ($this->can(ViewCompanySspAssetPermissionPlugin::KEY, $companyUserId) || $this->can(ViewBusinessUnitSspAssetPermissionPlugin::KEY, $companyUserId)) {
            $sspAssetCollectionTransfer = $this->selfServicePortalClient->getSspAssetCollection(
                (new SspAssetCriteriaTransfer())->setCompanyUser($companyUserTransfer),
            );

            foreach ($sspAssetCollectionTransfer->getSspAssets() as $sspAssetTransfer) {
                $businessEntityTypes[$sspAssetTransfer->getReferenceOrFail()] = $sspAssetTransfer->getNameOrFail();
            }
        }

        $businessEntityTypes[static::FILE_ATTACHMENT_TYPE_NONE] = static::SSP_ASSET_FILE_ATTACHMENT_TYPE_NONE_LABEL;

        return $businessEntityTypes;
    }
}
