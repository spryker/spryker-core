<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\CompanyFile\Form\DataProvider;

use Spryker\Client\GlossaryStorage\GlossaryStorageClientInterface;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanyBusinessUnitFilesPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanyFilesPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanyUserFilesPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig as SharedSelfServicePortalConfig;
use SprykerFeature\Yves\SelfServicePortal\CompanyFile\Form\FileSearchFilterForm;
use SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig;

class FileSearchFilterFormDataProvider
{
    use PermissionAwareTrait;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PREFIX_ENTITY_TYPE = 'self_service_portal.company_file.file_search_filter_form.field.type';

    /**
     * @var array<string, string>
     */
    protected const ENTITY_TO_PERMISSION_MAP = [
        SharedSelfServicePortalConfig::ENTITY_TYPE_COMPANY_USER => ViewCompanyUserFilesPermissionPlugin::KEY,
        SharedSelfServicePortalConfig::ENTITY_TYPE_COMPANY => ViewCompanyFilesPermissionPlugin::KEY,
        SharedSelfServicePortalConfig::ENTITY_TYPE_COMPANY_BUSINESS_UNIT => ViewCompanyBusinessUnitFilesPermissionPlugin::KEY,
    ];

    /**
     * @param \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig $selfServicePortalConfig
     * @param \Spryker\Client\GlossaryStorage\GlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(
        protected SelfServicePortalConfig $selfServicePortalConfig,
        protected GlossaryStorageClientInterface $glossaryStorageClient
    ) {
    }

    /**
     * @param string $localeName
     *
     * @return array<string, array<string, string>>
     */
    public function getOptions(string $localeName): array
    {
        return [
            FileSearchFilterForm::OPTION_FILE_TYPES => $this->getFileTypesChoices(),
            FileSearchFilterForm::OPTION_ACCESS_LEVELS => $this->getAccessLevelChoices($localeName),
        ];
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
     * @param string $localeName
     *
     * @return array<string, string>
     */
    protected function getAccessLevelChoices(string $localeName): array
    {
        $accessLevelChoices = [];
        $translationKeys = [];

        foreach ($this->selfServicePortalConfig->getAttachableEntityTypesForCompanyFiles() as $entityType) {
            $translationKeys[] = $this->getTranslationKey($entityType);
        }

        $translatedEntityTypes = $this->glossaryStorageClient->translateBulk($translationKeys, $localeName);
        foreach ($this->selfServicePortalConfig->getAttachableEntityTypesForCompanyFiles() as $entityType) {
            if (isset(static::ENTITY_TO_PERMISSION_MAP[$entityType]) && !$this->can(static::ENTITY_TO_PERMISSION_MAP[$entityType])) {
                continue;
            }

            $accessLevelChoices[$entityType] = $translatedEntityTypes[$this->getTranslationKey($entityType)] ?? $entityType;
        }

        return $accessLevelChoices;
    }

    /**
     * @param string $entityType
     *
     * @return string
     */
    protected function getTranslationKey(string $entityType): string
    {
        return sprintf('%s.%s', static::GLOSSARY_KEY_PREFIX_ENTITY_TYPE, $entityType);
    }
}
